<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Program;
use App\Models\Testimoni;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramMentee;
use App\Models\ProofreadingPaket;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;

class ProofreadingController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    ){}

    public $title = 'Proofreading';

    public function index()
    {
        return view('pages.proofreading.index', [
            'title' => $this->title,
            'proofreadings' => Program::with(['media', 'proofreadingPakets'])->withCount(['proofreadingPakets'])->whereProgram('Proofreading')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first(),
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $proofreadings = Program::with(['media', 'proofreadingPakets'])->whereProgram('Proofreading')->where('judul', 'like', '%' . $search . '%')->latest()->paginate(6);
        return view('pages.proofreading.index', [
            'title' => $this->title,
            'proofreadings' => $proofreadings,
            'webResource' => WebResource::with('media')->first()

        ]);
    }

    public function show($slug)
    {
        return view('pages.proofreading.show', [
            'title' => $this->title,
            'proofreading' => Program::with(['media', 'proofreadingPakets', 'testimoni.mentee.media', 'testimoni'])->withAvg('testimoni', 'rating')->withCount(['proofreadingPakets', 'testimoni'])->whereProgram('Proofreading')->where('slug', $slug)->first(),
            'admin' => User::with('media')->role('super_admin')->first(),
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'paket' => 'required|exists:proofreading_pakets,id',
            'referral_code' => 'nullable|exists:users,referral_code',
        ], [
            'paket.required' => 'Paket harus dipilih',
            'paket.exists' => 'Paket tidak valid',
            'referral_code.exists' => 'Referral code tidak valid',
        ]);

        // cek referral bukan diri sendiri
        if($request->referral_code)
        {
            if(!validateReferralCode($request->referral_code))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral code tidak valid'
                ], 422);
            }
        }

        // cek bukan admin/mentor
        if(!validateUserToBuy())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa membeli program ini'
            ], 422);
        }

        // delete transaksi yang belum dibayar
        deleteUnpaidTransaction(ProgramMentee::class);

        DB::beginTransaction();
        try {
            $program = Program::where('slug', $slug)->first();
            
            $programMentee = ProgramMentee::create([
                'program_id' => $program->id,
                'paketable_type' => ProofreadingPaket::class,
                'paketable_id' => $request->paket,
                'mentor_id' => $request->mentor,
                'mentee_id' => auth()->id(),
            ]);

            $transaksi = Transaksi::create([
                'order_id' => "ANSA-PR-" . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_type' => ProgramMentee::class,
                'transaksiable_id' => $programMentee->id,
                'referral_code' => $request->referral_code ?? null,
                'total_harga' => $program->proofreadingPakets->find($request->paket)->harga,
            ]);

            DB::commit();

            $snapToken = $this->paymentService->processPayment($transaksi);

            if(!$snapToken)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat snap token'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);
        } catch (Exception $e) {
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
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
        ], [
            'rating.required' => 'Rating harus diisi',
            'rating.numeric' => 'Rating harus berupa angka',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'comment.required' => 'Testimoni harus diisi',
            'comment.string' => 'Testimoni harus berupa teks',
        ]);

        if(!validateReferralCode(Program::class))
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
                'testimoniable_type' => Program::class,
                'testimoniable_id' => Program::where('slug', $slug)->first()->id,
                'mentee_id' => auth()->id(),
                'rating' => $request->rating,
                'ulasan' => $request->comment,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Testimoni berhasil ditambahkan'
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
