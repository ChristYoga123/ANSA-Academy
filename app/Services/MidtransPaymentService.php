<?php

namespace App\Services;

use Exception;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaksi;
use Midtrans\Transaction;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Models\ProdukDigital;
use App\Models\ProgramMentee;
use App\Models\KelasAnsaMentee;
use App\Models\MentoringMentee;
use App\Models\ProofreadingMentee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Contracts\PaymentServiceInterface;
use App\Mail\Transaksi\UserNotificationMail;

class MidtransPaymentService implements PaymentServiceInterface
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVERKEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    private const STATUS_MAPPING = [
        'capture' => [
            'challenge' => 'Menunggu',
            'accept' => 'Sukses',
        ],
        'cancel' => 'Dibatalkan',
        'deny' => 'Dibatalkan',
        'settlement' => 'Sukses',
        'pending' => 'Menunggu',
        'expire' => 'Dibatalkan',
    ];

    public function processPayment(Transaksi $transaksi): string
    {
        $itemDetails = [
            'id' => $transaksi->order_id,
            'price' => $transaksi->total_harga,
            'quantity' => 1,
            'name' => "Pembayaran untuk transaksi {$transaksi->order_id}",
        ];

        $customerDetails = [
            'first_name' => $transaksi->mentee->name,
            'email' => $transaksi->mentee->email,
        ];

        $midtransParam = [
            'transaction_details' => [
                'order_id' => $transaksi->order_id,
                'gross_amount' => $transaksi->total_harga,
            ],
            'item_details' => [$itemDetails],
            'customer_details' => $customerDetails,
        ];

        try {
            return Snap::getSnapToken($midtransParam);
        } catch(Exception $e) {
            Log::error('Midtrans payment error: ' . $e->getMessage());
            return false;
        }
    }

    public function midtransCallback(Request $request)
    {
        DB::beginTransaction();
        try {
            $notif = $request->method() == 'POST' 
                ? new Notification() 
                : Transaction::status($request->order_id);

            $checkout = Transaksi::whereOrderId($notif->order_id)->firstOrFail();
            
            $status = self::STATUS_MAPPING[$notif->transaction_status] ?? 'failed';
            if ($notif->transaction_status === 'capture' || $notif->transaction_status === 'cancel') {
                $status = self::STATUS_MAPPING[$notif->transaction_status][$notif->fraud_status] ?? 'failed';
            }

            $checkout->status = $status;
            
            // Only process and send email if status is successful
            if ($checkout->status === 'Sukses') {
                // Khusus Produk Digital, jika status transaksi sukses, maka update stok produk berkurang 1
                if($checkout->transaksiable_type === ProdukDigital::class) {
                    $produk = ProdukDigital::find($checkout->transaksiable_id);
                    $produk->decrement('qty');
                    $produk->save();
                } elseif($checkout->transaksiable_type === ProgramMentee::class) {
                    $mentoringMentee = ProgramMentee::find($checkout->transaksiable_id);
                    $mentoringMentee->is_aktif = true;
                    $mentoringMentee->save();
                }

                // Only send email when status is successful
                Mail::to($checkout->mentee->email)->queue(new UserNotificationMail($checkout));
            }

            $checkout->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Midtrans Callback success',
            ]);
            
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
