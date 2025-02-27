<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Promo;
use App\Models\Program;
use App\Models\Testimoni;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramMentee;
use App\Models\MentoringPaket;
use App\Models\ProgramKategori;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;

class MentoringController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}

    public $title = 'Mentoring';

    public function index()
    {
        // Ambil promosi kategori untuk event
        $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Mentoring')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        
        // Ambil promosi produk untuk event
        $promoProducts = Promo::where('tipe', 'produk')
                            ->where('promoable_type', Program::class)
                            ->where('kategori', 'Mentoring')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->pluck('persentase', 'promoable_id');
        return view('pages.mentoring.index', [
            'title' => $this->title,
            'mentorings' => Program::with(['media', 'mentoringPakets'])->withCount(['mentors', 'mentoringPakets'])->whereProgram('Mentoring')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first(),
            'kategories' => ProgramKategori::all(),
            'promoKategori' => $promoKategori,
            'promoProducts' => $promoProducts,
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category;
        $query = Program::with(['media', 'mentoringPakets'])
            ->withCount(['mentors', 'mentoringPakets'])
            ->whereProgram('Mentoring');

        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->whereHas('programKategori', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }
        return view('pages.mentoring.index', [
            'title' => $this->title,
            'mentorings' => $query->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first(),
            'kategories' => ProgramKategori::all(),
        ]);
    }

    public function show($slug)
    {
        $mentoring = Program::with(['media', 'mentoringPakets', 'mentors.media', 'mentors.testimoni', 'testimoni.mentee.media', 'testimoni'])
            ->withCount(['mentoringPakets', 'mentors', 'testimoni'])
            ->withAvg('testimoni', 'rating')
            ->whereProgram('Mentoring')
            ->where('slug', $slug)
            ->first();

        $promoProduct = Promo::where('tipe', 'produk')
                            ->where('promoable_type', 'App\Models\Program')
                            ->where('promoable_id', $mentoring->id)
                            ->where('kategori', 'Mentoring')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        $promoKategori = null;
        $promoKategori = Promo::where('tipe', 'kategori')
                        ->where('kategori', 'Mentoring')
                        ->where('aktif', true)
                        ->where(function($query) {
                            $query->whereNull('tanggal_berakhir')
                                    ->orWhere('tanggal_berakhir', '>=', now());
                        })
                        ->first();

        $activePromo = $promoProduct ?? $promoKategori;

        $canGiveTestimoni = validateUserToGiveTestimoni(Program::class, $mentoring->id);
        
        return view('pages.mentoring.show', [
            'title' => $this->title,
            'mentoring' => $mentoring,
            'canGiveTestimoni' => $canGiveTestimoni,
            'activePromo' => $activePromo,
            'webResource' => WebResource::with('media')->first(),
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'paket' => 'required|integer|exists:mentoring_pakets,id',
            'mentor' => 'required|integer|exists:users,id',
            'referral_code' => 'nullable',
        ], [
            'paket.required' => 'Paket harus dipilih',
            'paket.integer' => 'Paket harus dipilih',
            'paket.exists' => 'Paket tidak valid',
            'mentor.required' => 'Mentor harus dipilih',
            'mentor.integer' => 'Mentor harus dipilih',
            'mentor.exists' => 'Mentor tidak valid',
        ]);

        // cek validasi referral code
        if($request->referral_code)
        {
            $isValidReferral = validateReferralCode($request->referral_code);
            $isValidKupon = validateKupon($request->referral_code);

            if(!$isValidReferral && !$isValidKupon)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode referral atau kupon tidak valid'
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

            // cek jika referral code valid, harga berkurang 5%
            $currentPrice = $program->mentoringPakets->find($request->paket)->harga;

            if($request->referral_code && validateReferralCode($request->referral_code))
            {
                $currentPrice = $currentPrice - ($currentPrice * 0.05);
            } else if($request->referral_code && validateKupon($request->referral_code))
            {
                $kupon = Promo::where('kode', $request->referral_code)
                    ->where('aktif', true)
                    ->where('tipe', 'kupon')
                    ->where(function($query) {
                        $query->whereNull('tanggal_berakhir')
                                ->orWhere('tanggal_berakhir', '>=', now());
                    })
                    ->first();

                if($kupon)
                {
                    $currentPrice = $currentPrice - ($currentPrice * ($kupon->persentase / 100));
                }
            }

            // cek apakah ada promo yang berlaku
            $promoProduct = Promo::where('tipe', 'produk')
                            ->where('promoable_type', 'App\Models\Program')
                            ->where('promoable_id', $program->id)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();

            $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Mentoring')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();

            if($promoProduct)
            {
                $currentPrice = $currentPrice - ($currentPrice * ($promoProduct->persentase / 100));
            } else if($promoKategori)
            {
                $currentPrice = $currentPrice - ($currentPrice * ($promoKategori->persentase / 100));
            }

            // dd($currentPrice);

            $transaksi = Transaksi::create([
                'order_id' => "ANSA-MNTR-" . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_type' => ProgramMentee::class,
                'transaksiable_id' => $programMentee->id,
                'referral_code' => $request->referral_code ?? null,
                'total_harga' => floor($currentPrice),
            ]);

            // dd($transaksi);

            DB::commit();

            $snapToken = $this->paymentService->processPayment($transaksi);

            // dd($snapToken);

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
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->order_id,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan. Harap coba lagi nanti atau hubungi Admin'
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
