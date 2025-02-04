<?php 
use App\Models\Transaksi;
use App\Models\ProgramMentee;
use App\Models\ProofreadingPaket;
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
?>