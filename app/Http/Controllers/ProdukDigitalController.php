<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProdukDigital;
use Illuminate\Support\Facades\DB;
use App\Contracts\PaymentServiceInterface;
use App\Models\Testimoni;
use App\Models\WebResource;

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
            'produkDigitals' => ProdukDigital::latest()->paginate(6),
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function show($slug)
    {
        $produkDigital = ProdukDigital::withCount(['testimoni'])->with(['testimoni', 'testimoni.mentee.media'])->where('slug', $slug)->first();

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
            'produkDigitals' => $produkDigitals,
            'webResource' => WebResource::with('media')->first()
        ]);
    }

    public function beli(Request $request, $slug)
    {
        $request->validate([
            'referral_code' => 'nullable|exists:users,referral_code'
        ], [
            'referral_code.exists' => 'Referral code tidak valid'
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
            if(!validateReferralCode($request->referral_code))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral code tidak valid'
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

            $transaksi = Transaksi::create([
                'order_id' => 'ANSA-PD-' . Str::random(6),
                'mentee_id' => auth()->id(),
                'transaksiable_id' => $produkDigital->id,
                'transaksiable_type' => ProdukDigital::class,
                'total_harga' => $produkDigital->harga,
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
