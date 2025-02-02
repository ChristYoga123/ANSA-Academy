<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaksi;
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
        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => ProdukDigital::latest()->paginate(6)
        ]);
    }

    public function show($slug)
    {
        $produkDigital = ProdukDigital::where('slug', $slug)->firstOrFail();

        return view('pages.produk-digital.show', [
            'title' => $this->title,
            'produkDigital' => $produkDigital
        ]);
    }

    public function search(Request $request)
    {
        $produkDigitals = ProdukDigital::where('judul', 'like', '%' . $request->search . '%')->latest()->paginate(6);

        return view('pages.produk-digital.index', [
            'title' => $this->title,
            'produkDigitals' => $produkDigitals
        ]);
    }

    public function beli($slug)
    {
        DB::beginTransaction();
        try
        {
            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-PD-' . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_id' => ProdukDigital::where('slug', $slug)->firstOrFail()->id,
                'transaksiable_type' => ProdukDigital::class,
                'total_harga' => ProdukDigital::where('slug', $slug)->firstOrFail()->harga,
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
                'snap_token' => $snapToken
            ]);
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
