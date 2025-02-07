@extends('layouts.app')

@section('content')
    <section class="payment-status my-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 mx-auto wow fadeInUp" data-wow-delay="100ms">
                    <div class="payment-status__single text-center">
                        <div class="payment-status__icon success mb-4">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        <div class="payment-status__content">
                            <h2 class="payment-status__title">Pembayaran Berhasil!</h2>
                            <p class="payment-status__text mt-3">
                                Terima kasih atas pembayaran Anda. Transaksi telah berhasil diproses.
                            </p>
                            <div class="payment-status__btn-box mt-4">
                                <a href="{{ route('filament.mentee.resources.transaksis.index') }}" class="thm-btn">
                                    <span class="icon-angles-right"></span>Masuk ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
