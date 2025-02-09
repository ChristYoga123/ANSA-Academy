<?php

use App\Models\MentoringPaket;
use App\Models\Program;
use App\Models\Transaksi;
use App\Models\ProgramMentee;
use App\Models\ProofreadingPaket;
use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if(!function_exists('deleteUnpaidTransaction'))
{
    function deleteUnpaidTransaction($transaksiType)
    {
        $transaksi = Transaksi::where('transaksiable_type', $transaksiType)
            ->whereMenteeId(auth()->id())
            ->where('status', '!=', 'Sukses')
            ->first();
        
        if($transaksi) {
            if($transaksi->transaksiable_type === ProgramMentee::class) {
                $programMentee = ProgramMentee::find($transaksi->transaksiable_id);
                if($programMentee->paketable_type === ProofreadingPaket::class && !$programMentee->proofreadingMenteeSubmission) {
                    $programMentee->delete();
                }elseif($programMentee->paketable_type === MentoringPaket::class && !$programMentee->mentoringMenteeJadwal) {
                    $programMentee->delete();
                }else {
                    $programMentee->delete();
                }
            }
            $transaksi->delete();
        }
    }
}

if(!function_exists('validateUserToBuy'))
{
    function validateUserToBuy()
    {
        if(Auth::user()->hasRole(['super_admin', 'mentor']))
        {
            return false;
        }

        return true;
    }
}

if(!function_exists('validateReferralCode'))
{
    function validateReferralCode($referralCode)
    {
        // jika yang memasukkan referral code adalah mentor atau super admin
        if(Auth::user()->hasRole(['super_admin', 'mentor']))
        {
            return false;
        }

        // jika referral code yang dimasukkan adalah referral code dari diri sendiri
        if(Auth::user()->referral_code === $referralCode)
        {
            return false;
        }

        return true;
    }
}

if(!function_exists('validateTestimoni'))
{
    function validateTestimoni($programType, $programId)
    {
        $testimoniExist = Testimoni::whereTestimoniableType($programType)
            ->whereTestimoniableId($programId)
            ->whereMenteeId(auth()->id())
            ->exists();

        if($testimoniExist)
        {
            return false;
        }

        return true;
    }
}

if(!function_exists('validateUserToGiveTestimoni'))
{
    function validateUserToGiveTestimoni($programType, $programId)
    {
        if($programType === Program::class)
        {
            $programMentee = ProgramMentee::whereProgramId($programId)
                ->whereMenteeId(auth()->id())
                ->where('is_aktif', true)
                ->first();
    
            if(!$programMentee)
            {
                return false;
            }
        } else
        {
            $transaksiMentee = Transaksi::whereTransaksiableId($programId)
                ->whereTransaksiableType($programType)
                ->whereStatus('Sukses')
                ->whereMenteeId(auth()->id())
                ->first();

            if(!$transaksiMentee)
            {
                return false;
            }
        }

        return true;
    }
}
?>