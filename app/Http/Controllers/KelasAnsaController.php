<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Promo;
use App\Models\Program;
use App\Models\Testimoni;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProgramMentee;
use App\Models\KelasAnsaPaket;
use App\Models\KelasAnsaDetail;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;

class KelasAnsaController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}

    public $title = 'Kelas Ansa';

    public function index()
    {
        // Ambil promosi kategori untuk event
        $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Kelas ANSA')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        
        // Ambil promosi produk untuk event
        $promoProducts = Promo::where('tipe', 'produk')
                            ->where('promoable_type', Program::class)
                            ->where('kategori', 'Kelas ANSA')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->pluck('persentase', 'promoable_id');
        return view('pages.kelas-ansa.index', [
            'title' => $this->title,
            'kelas' => Program::with(['media', 'kelasAnsaPakets', 'kelasAnsaDetail'])->withCount(['kelasAnsaPakets', 'mentors'])->whereProgram('Kelas Ansa')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first(),
            'promoKategori' => $promoKategori,
            'promoProducts' => $promoProducts
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

        $promoProduct = Promo::where('tipe', 'produk')
                            ->where('promoable_type', 'App\Models\Program')
                            ->where('promoable_id', $kelas->id)
                            ->where('kategori', 'Kelas ANSA')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        $promoKategori = null;
        $promoKategori = Promo::where('tipe', 'kategori')
                        ->where('kategori', 'Kelas ANSA')
                        ->where('aktif', true)
                        ->where(function($query) {
                            $query->whereNull('tanggal_berakhir')
                                    ->orWhere('tanggal_berakhir', '>=', now());
                        })
                        ->first();

        $activePromo = $promoProduct ?? $promoKategori;

        $canGiveTestimoni = validateUserToGiveTestimoni(Program::class, $kelas->id);
        return view('pages.kelas-ansa.show', [
            'title' => $this->title,
            'kelasAnsa' => $kelas,
            'canGiveTestimoni' => $canGiveTestimoni,
            'activePromo' => $activePromo
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'paket' => 'required|exists:kelas_ansa_pakets,id',
            'referral_code' => 'nullable'
        ], [
            'paket.required' => 'Paket harus dipilih.',
            'paket.exists' => 'Paket tidak valid.',
        ]);

        if(!validateUserToBuy())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa membeli kelas ini.'
            ], 403);
        }

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

            $currentPrice = $kelasAnsaPaket->harga;

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
                            ->where('promoable_id', $kelasAnsaPaket->kelas_ansa_id)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();

            $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Kelas ANSA')
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

            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-KLS-' . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_id' => $programMentee->id,
                'transaksiable_type' => ProgramMentee::class,
                'total_harga' => $currentPrice,
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
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->order_id
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan. Silahkan coba lagi.'
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
