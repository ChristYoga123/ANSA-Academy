<?php

namespace App\Contracts;

use App\Models\Transaksi;

interface PaymentServiceInterface
{
    public function processPayment(Transaksi $transaksi): string;
}
