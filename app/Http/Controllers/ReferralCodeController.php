<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralCodeController extends Controller
{
    public function check(Request $request)
    {
        if(!validateReferralCode($request->referral_code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Referral code tidak valid'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Referral code valid'
        ]);
    }
}
