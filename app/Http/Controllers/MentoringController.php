<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Program;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramMentee;
use App\Models\MentoringPaket;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;
use App\Models\Testimoni;

class MentoringController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}

    public $title = 'Mentoring';

    public function index()
    {
        return view('pages.mentoring.index', [
            'title' => $this->title,
            'mentorings' => Program::with(['media', 'mentoringPakets'])->withCount(['mentors', 'mentoringPakets'])->whereProgram('Mentoring')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        return view('pages.mentoring.index', [
            'title' => $this->title,
            'mentorings' => Program::with(['media', 'mentoringPakets'])->withCount(['mentors', 'mentoringPakets', 'testimoni'])->whereHas('testimoni', function($query)
            {
                $query->whereTestimoniableType(Program::class);
            })->whereProgram('Mentoring')->where('judul', 'like', '%' . $search . '%')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function show($slug)
    {
        return view('pages.mentoring.show', [
            'title' => $this->title,
            'mentoring' => Program::with(['media', 'mentoringPakets', 'mentors.media', 'testimoni.mentee.media', 'testimoni'])
                ->withCount(['mentoringPakets', 'mentors', 'testimoni'])
                ->withAvg('testimoni', 'rating')
                ->whereProgram('Mentoring')
                ->where('slug', $slug)
                ->first(),
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'paket' => 'required|integer|exists:mentoring_pakets,id',
            'mentor' => 'required|integer|exists:users,id',
            'referral_code' => 'nullable|exists:users,referral_code',
        ], [
            'paket.required' => 'Paket harus dipilih',
            'paket.integer' => 'Paket harus dipilih',
            'paket.exists' => 'Paket tidak valid',
            'mentor.required' => 'Mentor harus dipilih',
            'mentor.integer' => 'Mentor harus dipilih',
            'mentor.exists' => 'Mentor tidak valid',
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

            // cek jika mentee memilih mentoring dengan paket atau jenis mentoring yang sama
            $cekKeaktifanMentoring = ProgramMentee::where('mentee_id', auth()->id())
                ->wherePaketableType(MentoringPaket::class)
                ->whereProgramId($program->id)
                ->where('is_aktif', true)
                ->first();

            if($cekKeaktifanMentoring)
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda masih aktif di mentoring ini. Harap selesaikan program dengan paket terkait kecuali Anda membeli progam mentoring baru'
                ], 422);
            }
            
            $programMentee = ProgramMentee::create([
                'program_id' => $program->id,
                'paketable_type' => MentoringPaket::class,
                'paketable_id' => $request->paket,
                'mentor_id' => $request->mentor,
                'mentee_id' => auth()->id(),
            ]);

            $transaksi = Transaksi::create([
                'order_id' => "ANSA-MNTR-" . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_type' => ProgramMentee::class,
                'transaksiable_id' => $programMentee->id,
                'referral_code' => $request->referral_code ?? null,
                'total_harga' => $program->mentoringPakets->find($request->paket)->harga,
            ]);

            DB::commit();

            $snapToken = $this->paymentService->processPayment($transaksi);

            if(!$snapToken)
            {
                DB::rollBack();
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
