@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $produkDigital->judul,
    'pageHeaderImg' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'bgImage' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'breadcrumb' => [
    [
    'name' => $produkDigital->judul,
    'url' => route('produk-digital.show', $produkDigital->slug)
    ]
    ]
    ])
    <!--Start Product Details-->
    <section class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__img">
                        <img src="{{ $produkDigital->getFirstMediaUrl('produk-digital-thumbnail') }}"
                            alt="{{ $produkDigital->judul }}" style="mix-blend-mode: normal !important;" width="630px"
                            height="625px">
                    </div>
                </div>

                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__top">
                        <h3 class="product-details__title">
                            {{ $produkDigital->judul }} <span>
                                Rp. {{ number_format($produkDigital->harga, 0, ',', '.') }}
                            </span>
                        </h3>
                    </div>
                    <div class="product-details__reveiw">
                        <span class="p-1 rounded bg-primary text-white">Platform: {{ $produkDigital->platform }}</span>
                    </div>
                    <div class="product-details__content">
                        <p class="product-details__content-text1">{!! $produkDigital->deskripsi !!}</p>
                        <p class="product-details__content-text2">Stok:
                            {{ $produkDigital->is_unlimited ? 'Tidak Terbatas' : $produkDigital->qty }} <br>
                            <span
                                class="text-{{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'success' : 'danger' }}">
                                {{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </p>
                    </div>
                    <!-- Add Referral Code Input -->
                    <div class="product-details__buttons mt-4" style="margin-bottom: -40px; width: 100%;">
                        <div class="course-details__search-form">
                            <input type="text" placeholder="Masukkan referral code" name="referral_code">
                        </div>
                    </div>
                    <div class="product-details__buttons">
                        <div class="product-details__buttons-1">
                            <button onclick="beli()" class="thm-btn">Beli Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Product Details-->
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        function beli() {
            @guest
            window.location.href = `{{ route('filament.mentee.auth.login') }}`
            return;
        @endguest
        $.ajax({
            url: `{{ route('produk-digital.beli', $produkDigital->slug) }}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                referral_code: $('input[name="referral_code"]').val()
            },
            beforeSend: function() {
                $('button[onclick="beli()"]').attr('disabled', true);
            },
            success: function(response) {
                $('button[onclick="beli()"]').attr('disabled', false);

                if (response.status === 'success' && response.snap_token) {
                    snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            console.log('success');
                            console.log(result);
                        },
                        onPending: function(result) {
                            console.log('pending');
                            console.log(result);
                        },
                        onError: function(result) {
                            console.log('error');
                            console.log(result);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                $('button[onclick="beli()"]').attr('disabled', false);

                let errorMessage = 'Terjadi kesalahan. Mohon coba lagi.';
                try {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }

                toastr.error(errorMessage);
            }
        })
        }
    </script>
@endpush
