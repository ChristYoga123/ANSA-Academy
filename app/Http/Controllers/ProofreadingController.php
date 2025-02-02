<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Program;
use App\Models\Transaksi;
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
            'proofreadings' => Program::with(['media', 'proofreadingPakets'])->withCount(['proofreadingPakets'])->whereProgram('Proofreading')->latest()->paginate(6)
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $proofreadings = Program::with(['media', 'proofreadingPakets'])->whereProgram('Proofreading')->where('judul', 'like', '%' . $search . '%')->latest()->paginate(6);
        return view('pages.proofreading.index', [
            'title' => $this->title,
            'proofreadings' => $proofreadings
        ]);
    }

    public function show($slug)
    {
        return view('pages.proofreading.show', [
            'title' => $this->title,
            'proofreading' => Program::with(['media', 'proofreadingPakets'])->withCount(['proofreadingPakets'])->whereProgram('Proofreading')->where('slug', $slug)->firstOrFail(),
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
        // if(!validateUserToBuy())
        // {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Anda tidak bisa membeli program ini'
        //     ], 422);
        // }

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
                'order_id' => "ANSA-MENTORING-" . Str::random(6),
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
}
