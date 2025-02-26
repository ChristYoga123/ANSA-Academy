<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Promo;
use App\Models\Transaksi;
use App\Models\WebResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Contracts\PaymentServiceInterface;

class EventController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}
    public $title = 'Event';

    public function index()
    {
        $events = Event::with(['media', 'eventJadwals'])->withCount('eventJadwals')->latest()->paginate(6);
        
        // Ambil promosi kategori untuk event
        $promoKategori = Promo::where('tipe', 'kategori')
                            ->where('kategori', 'Event')
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->first();
        
        // Ambil promosi produk untuk event
        $promoProducts = Promo::where('tipe', 'produk')
                            ->where('promoable_type', Event::class)
                            ->where('aktif', true)
                            ->where(function($query) {
                                $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                            })
                            ->pluck('persentase', 'promoable_id');
                            
        return view('pages.event.index', [
            'title' => $this->title,
            'events' => $events,
            'webResource' => WebResource::with('media')->first(),
            'promoKategori' => $promoKategori,
            'promoProducts' => $promoProducts
        ]);
    }

    public function show($slug)
    {
        $event = Event::with(['media', 'eventJadwals'])->withCount(['transaksi'])->where('slug', $slug)->first();
    
        // Cek promosi spesifik produk
        $promoProduct = Promo::where('tipe', 'produk')
                             ->where('promoable_type', 'App\Models\Event')
                             ->where('promoable_id', $event->id)
                             ->where('aktif', true)
                             ->where(function($query) {
                                 $query->whereNull('tanggal_berakhir')
                                       ->orWhere('tanggal_berakhir', '>=', now());
                             })
                             ->first();
        
        // Cek promosi kategori jika tidak ada promosi produk
        $promoKategori = null;
        if (!$promoProduct) {
            $promoKategori = Promo::where('tipe', 'kategori')
                                 ->where('kategori', 'Event')
                                 ->where('aktif', true)
                                 ->where(function($query) {
                                     $query->whereNull('tanggal_berakhir')
                                           ->orWhere('tanggal_berakhir', '>=', now());
                                 })
                                 ->first();
        }
        
        // Tentukan diskon yang berlaku
        $activePromo = $promoProduct ?? $promoKategori;
        
        return view('pages.event.show', [
            'title' => $this->title,
            'event' => $event,
            'activePromo' => $activePromo
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'referral_code' => 'nullable'
        ]);

        // Perbaikan logika validasi referral/kupon
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

        if(!validateUserToBuy())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak bisa membeli event ini'
            ], 403);
        }

        deleteUnpaidTransaction(Event::class);

        DB::beginTransaction();
        try
        {
            $event = Event::where('slug', $slug)->withCount('transaksi')->first();

            // cek apakah event belum atau sudah menutup pendaftaran
            if(Carbon::now()->lt($event->waktu_open_registrasi) || Carbon::now()->gt($event->waktu_close_registrasi))
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pendaftaran event ini belum dibuka atau sudah ditutup'
                ], 403);
            }

            // cek mentee sudah pernah mendaftar event ini
            if(Transaksi::where('mentee_id', auth()->user()->id)->where('transaksiable_type', Event::class)->where('transaksiable_id', $event->id)->whereStatus('Sukses')->exists())
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mendaftar event ini'
                ], 403);
            }

            // cek jika kuota sudah penuh
            if($event->kuota <= $event->transaksi_count)
            {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Slot event ini sudah penuh'
                ], 403);
            }

            $currentPrice = $event->harga ?? 0;

            // Perbaikan logika penerapan diskon referral
            if($request->referral_code && validateReferralCode($request->referral_code))
            {
                $currentPrice = $currentPrice - ($currentPrice * 0.05);
            }
            // Perbaikan: Penerapan diskon kupon jika kode adalah kupon
            else if($request->referral_code && validateKupon($request->referral_code))
            {
                $kupon = Promo::where('kode', $request->referral_code)
                    ->where('aktif', true)
                    ->where('tipe', 'kupon')
                    ->where(function($query) {
                        $query->whereNull('tanggal_berakhir')
                            ->orWhere('tanggal_berakhir', '>=', now());
                    })
                    ->first();
                    
                if($kupon) {
                    $currentPrice = $currentPrice - ($currentPrice * ($kupon->persentase / 100));
                }
            }

            // cek apakah ada promo yang berlaku
            $promoProduct = Promo::where('tipe', 'produk')
                                ->where('promoable_type', 'App\Models\Event')
                                ->where('promoable_id', $event->id)
                                ->where('aktif', true)
                                ->where(function($query) {
                                    $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                                })
                                ->first();

            $promoKategori = Promo::where('tipe', 'kategori')
                                ->where('kategori', 'Event')
                                ->where('aktif', true)
                                ->where(function($query) {
                                    $query->whereNull('tanggal_berakhir')
                                        ->orWhere('tanggal_berakhir', '>=', now());
                                })
                                ->first();

            if($promoProduct)
            {
                $currentPrice = $currentPrice - ($currentPrice * ($promoProduct->persentase / 100));
            }else if($promoKategori)
            {
                $currentPrice = $currentPrice - ($currentPrice * ($promoKategori->persentase / 100));
            }
            
            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-EVT-' . Str::random(6),
                'mentee_id' => auth()->user()->id,
                'transaksiable_id' => Event::where('slug', $slug)->first()->id,
                'transaksiable_type' => Event::class,
                'total_harga' => $currentPrice,
                'status' => $event->pricing == 'gratis' ? 'Sukses' : 'Menunggu',
                'referral_code' => $request->referral_code ?? null,
                'promo_kode' => $request->referral_code ?? null
            ]);

            DB::commit();

            if($event->pricing === 'gratis')
            {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil mendaftar event ini',
                    'transaksi_id' => $transaksi->order_id
                ], 200);
            }
            
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
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->order_id
            ]);
        }catch(Exception $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
