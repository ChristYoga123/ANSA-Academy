<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Program;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramMentee;
use App\Models\KelasAnsaPaket;
use App\Models\KelasAnsaDetail;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;
use App\Models\Testimoni;

class KelasAnsaController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}

    public $title = 'Kelas Ansa';

    public function index()
    {
        return view('pages.kelas-ansa.index', [
            'title' => $this->title,
            'kelas' => Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail'])->withCount(['kelasAnsaPakets', 'mentors'])->whereProgram('Kelas Ansa')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $kelas = Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail'])->withCount(['kelasAnsaPakets'])->whereProgram('Kelas Ansa')->where('judul', 'like', '%' . $search . '%')->paginate(6);

        return view('pages.kelas-ansa.index', [
            'title' => $this->title,
            'kelas' => $kelas,
            'webResource' => WebResource::with('media')->first()

        ]);
    }

    public function show($slug)
    {
        $kelas = Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail', 'mentors.media', 'testimoni.mentee.media', 'testimoni'])->withCount(['testimoni'])->withAvg('testimoni', 'rating')->whereProgram('Kelas Ansa')->where('slug', $slug)->first();

        return view('pages.kelas-ansa.show', [
            'title' => $this->title,
            'kelasAnsa' => $kelas
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'paket' => 'required|exists:kelas_ansa_pakets,id',
            'referral_code' => 'nullable|exists:users,referral_code'
        ], [
            'paket.required' => 'Paket harus dipilih.',
            'paket.exists' => 'Paket tidak valid.',
            'referral_code.exists' => 'Referral code tidak valid.'
        ]);

        if(!validateUserToBuy())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa membeli kelas ini.'
            ], 403);
        }

        if($request->referral_code)
        {
            if(!validateReferralCode($request->referral_code))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral code tidak valid.'
                ], 403);
            }
        }

        deleteUnpaidTransaction(ProgramMentee::class);

        DB::beginTransaction();

        try
        {
            $kelasAnsaPaket = KelasAnsaPaket::find($request->paket);
            
            $programMentee = ProgramMentee::create([
                'program_id' => Program::where('slug', $slug)->first()->id,
                'mentee_id' => auth()->id(),
                'paketable_id' => $kelasAnsaPaket->id,
                'paketable_type' => KelasAnsaPaket::class,
            ]);

            // cek waktu pendaftaran
            $kelasAnsaDetail = KelasAnsaDetail::where('kelas_ansa_id', $programMentee->program_id)->first();
            if(Carbon::now()->lt($kelasAnsaDetail->waktu_open_registrasi) || Carbon::now()->gt($kelasAnsaDetail->waktu_close_registrasi))
            {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Waktu pendaftaran belum dimulai atau sudah selesai.'
                ], 403);
            }

            // cek apakah mentee sudah terdaftar di program ini dengan case program sama paket sama atau program sama paket beda
            $existingRegistration = ProgramMentee::where('mentee_id', auth()->id())
                ->where('program_id', $programMentee->program_id)
                ->where('paketable_type', KelasAnsaPaket::class)
                ->where(function($query) use ($kelasAnsaPaket) {
                    $query->where('paketable_id', $kelasAnsaPaket->id)
                        ->orWhereHas('program', function($subQuery) use ($kelasAnsaPaket) {
                            $subQuery->where('id', $kelasAnsaPaket->kelas_ansa_id);
                        });
                })
                ->whereIsAktif(true)
                ->exists();

            if ($existingRegistration) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah terdaftar di paket ini.'
                ], 403);
            }
            // if(ProgramMentee::where('mentee_id', auth()->id())->where('paketable_id', $kelasAnsaPaket->id)->where('paketable_type', KelasAnsaPaket::class)->whereIsAktif(true)->exists())
            // {
            //     DB::rollBack();

            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Anda sudah terdaftar di paket ini.'
            //     ], 403);
            // }

            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-KLS-' . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_id' => $programMentee->id,
                'transaksiable_type' => ProgramMentee::class,
                'total_harga' => $kelasAnsaPaket->harga,
                'referral_code' => $request->referral_code ?? null,
            ]);

            DB::commit();

            $snapToken = $this->paymentService->processPayment($transaksi);

            if(!$snapToken)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat snap token.'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }    
    }

    public function storeTestimoni(Request $request, $slug)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string',
        ], [
            'rating.required' => 'Rating harus dipilih',
            'rating.integer' => 'Rating harus dipilih',
            'rating.between' => 'Rating harus diantara 1 sampai 5',
            'comment.required' => 'Testimoni harus diisi',
            'comment.string' => 'Testimoni harus diisi',
        ]);

        if(!validateTestimoni(Program::class, Program::where('slug', $slug)->first()->id))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah memberikan testimoni'
            ], 422);
        }

        DB::beginTransaction();

        try
        {
            Testimoni::create([
                'rating' => $request->rating,
                'ulasan' => $request->comment,
                'testimoniable_type' => Program::class,
                'testimoniable_id' => Program::where('slug', $slug)->first()->id,
                'mentee_id' => auth()->id(),
            ]);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Testimoni berhasil dikirim'
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
