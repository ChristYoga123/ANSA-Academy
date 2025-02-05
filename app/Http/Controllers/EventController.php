<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;
use App\Models\WebResource;

class EventController extends Controller
{
    public function __construct(
        protected PaymentServiceInterface $paymentService
    )
    {}
    public $title = 'Event';

    public function index()
    {
        return view('pages.event.index', [
            'title' => $this->title,
            'events' => Event::with(['media', 'eventJadwals'])->withCount('eventJadwals')->latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function show($slug)
    {
        $event = Event::with(['media', 'eventJadwals'])->withCount(['transaksi'])->where('slug', $slug)->first();
        return view('pages.event.show', [
            'title' => $this->title,
            'event' => $event
        ]);
    }

    public function beli($slug)
    {
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
            
            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-EVT-' . Str::random(6),
                'mentee_id' => auth()->user()->id,
                'transaksiable_id' => Event::where('slug', $slug)->first()->id,
                'transaksiable_type' => Event::class,
                'total_harga' => $event->harga,
                'status' => $event->pricing == 'gratis' ? 'Sukses' : 'Menunggu'
            ]);

            DB::commit();

            if($event->pricing === 'gratis')
            {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil mendaftar event ini'
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
}
