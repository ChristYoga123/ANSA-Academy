<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class ReferralCodeController extends Controller
{
    public function check(Request $request)
    {
        if($request->paket)
        {
            $request->validate([
                'paket' => 'required|integer|exists:mentoring_pakets,id',
            ], [
                'paket.required' => 'Paket harus diisi',
            ]);
        }

        $condition = false;
        $tipe = null;
        $persentase = 0;
        // Cek referral code terlebih dahulu
        if(validateReferralCode($request->referral_code)) {
            $condition = true;
            $tipe = 'referral';
        } 
        // Jika bukan referral code, cek apakah kupon
        else if(validateKupon($request->referral_code)) {
            $condition = true;
            $tipe = 'kupon';
            $persentase = Promo::where('kode', $request->referral_code)
                    ->where('aktif', true)
                    ->where('tipe', 'kupon')
                    ->where(function($query) {
                        $query->whereNull('tanggal_berakhir')
                            ->orWhere('tanggal_berakhir', '>=', now());
                    })
                    ->first()->persentase;
        }

        return response()->json([
            'status' => $condition ? 'success' : 'error',
            'message' => $condition ? 'Kode referral/kupon valid' : 'Kode referral/kupon tidak valid',
            'tipe' => $tipe,
            'persentase' => $persentase,
        ], $condition ? 200 : 422);
    }
}
