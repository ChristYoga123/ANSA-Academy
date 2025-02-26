<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Promo;
use App\Models\Testimoni;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProdukDigital;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;

class ProdukDigitalController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentServiceInterface
    )
    {}
    public $title = 'Produk Digital';

    public function index()
    {
        // Ambil promosi kategori untuk event
        $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Produk Digital')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        
        // Ambil promosi produk untuk event
        $promoProducts = Promo::where('tipe', 'produk')
                            ->where('promoable_type', ProdukDigital::class)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->pluck('persentase', 'promoable_id');
        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => ProdukDigital::latest()->paginate(6),
            'webResource' => WebResource::with('media')->first(),
            'promoKategori' => $promoKategori,
            'promoProducts' => $promoProducts
        ]);
    }

    public function show($slug)
    {
        $produkDigital = ProdukDigital::withCount(['testimoni'])->with(['testimoni', 'testimoni.mentee.media', 'mentor'])->where('slug', $slug)->first();

        $promoProduct = Promo::where('tipe', 'produk')
                            ->where('promoable_type', 'App\Models\ProdukDigital')
                            ->where('promoable_id', $produkDigital->id)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        $promoKategori = null;
        $promoKategori = Promo::where('tipe', 'kategori')
                        ->where('kategori', 'Produk Digital')
                        ->where('aktif', true)
                        ->where(function($query) {
                            $query->whereNull('tanggal_berakhir')
                                    ->orWhere('tanggal_berakhir', '>=', now());
                        })
                        ->first();

        $activePromo = $promoProduct ?? $promoKategori;
        // dd($activePromo);
        $canGiveTestimoni = validateUserToGiveTestimoni(ProdukDigital::class, $produkDigital->id);
        return view('pages.produk-digital.show', [
            'title' => $this->title,
            'produkDigital' => $produkDigital,
            'canGiveTestimoni' => $canGiveTestimoni,
            'activePromo' => $activePromo,
        ]);
    }

    public function search(Request $request)
    {
        $produkDigitals = ProdukDigital::where('judul', 'like', '%' . $request->search . '%')->latest()->paginate(6);

        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => $produkDigitals,
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'referral_code' => 'nullable'
        ]);
        // cek jika user admin/mentor
        if(!validateUserToBuy())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa membeli produk digital'
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

        // hapus transaksi yang belum dibayar
        deleteUnpaidTransaction(ProdukDigital::class);
        DB::beginTransaction();
        try
        {
            $produkDigital = ProdukDigital::where('slug', $slug)->firstOrFail();
            
            // cek jika produk memiliki qty dan qty <= 0
            if(!$produkDigital->is_unlimited && $produkDigital->qty === 0)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk digital tidak tersedia'
                ], 404);
            }

            $currentPrice = $produkDigital->harga;

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
                            ->where('promoable_type', 'App\Models\ProdukDigital')
                            ->where('promoable_id', $produkDigital->id)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();

            $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Produk Digital')
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
                'order_id' => 'ANSA-PD-' . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_id' => $produkDigital->id,
                'transaksiable_type' => ProdukDigital::class,
                'total_harga' => $currentPrice,
                'referral_code' => $request->referral_code ?? null,
            ]);

            $snapToken = $this->paymentServiceInterface->processPayment($transaksi);

            if(!$snapToken)
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal membuat snap token'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->order_id
            ]);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan, silahkan coba beberapa saat lagi'
            ], 500);
        }
    }

    public function storeTestimoni(Request $request, $slug)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required',
        ], [
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'rating.numeric' => 'Rating harus berupa angka',
            'comment.required' => 'Komentar harus diisi'
        ]);

        if(!validateTestimoni(ProdukDigital::class, ProdukDigital::where('slug', $slug)->first()->id))
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah memberikan testimoni'
            ], 403);
        }

        DB::beginTransaction();
        
        try
        {
            $produkDigital = ProdukDigital::where('slug', $slug)->firstOrFail();
            Testimoni::create([
                'mentee_id' => auth()->id(),
                'rating' => $request->rating,
                'ulasan' => $request->comment,
                'testimoniable_id' => $produkDigital->id,
                'testimoniable_type' => ProdukDigital::class
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
