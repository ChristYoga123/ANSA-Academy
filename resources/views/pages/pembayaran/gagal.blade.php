@extends('layouts.app')

@section('content')
    <section class="payment-status my-5">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 mx-auto wow fadeInUp" data-wow-delay="100ms">
                    <div class="payment-status__single text-center">
                        <div class="payment-status__icon failed mb-4">
                            <i class="fas fa-times-circle fa-5x text-danger"></i>
                        </div>
                        <div class="payment-status__content">
                            <h2 class="payment-status__title">Pembayaran Gagal</h2>
                            <p class="payment-status__text mt-3">
                                Mohon maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau hubungi tim support
                                kami.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
