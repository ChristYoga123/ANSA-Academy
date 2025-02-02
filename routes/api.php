<?php

use App\Services\MidtransPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('after-payment', [MidtransPaymentService::class, 'midtransCallback'])->name('after-payment');
