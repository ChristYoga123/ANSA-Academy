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
                                Terima kasih atas pembayaran Anda. Transaksi telah berhasil diproses. Harap mengonfirmasi
                                akun Admin untuk memverifikasi pembayaran Anda.
                            </p>
                            <div class="payment-status__btn-box mt-4">
                                <a href="https://wa.me/6283191260587?text={{ urlencode(
                                    "Halo Admin,\n\n" .
                                        "Saya ingin mengonfirmasi pembayaran untuk:\n\n" .
                                        'No. Order: *' .
                                        $transaksi->order_id .
                                        "*\n" .
                                        'Nama Mentee: *' .
                                        $transaksi->mentee->name .
                                        "*\n" .
                                        ($transaksi->transaksiable->mentor ? 'Nama Mentor: *' . $transaksi->transaksiable->mentor->name . "*\n" : '') .
                                        'Total Pembayaran: *Rp ' .
                                        number_format($transaksi->total_harga, 0, ',', '.') .
                                        "*\n\n" .
                                        'Mohon verifikasi pembayaran saya. Terima kasih 🙏',
                                ) }}"
                                    class="thm-btn btn-success">
                                    <span class="icon-angles-right"></span>Konfirmasi Admin Via Whatsapp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
