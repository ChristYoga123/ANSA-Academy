@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $produkDigital->judul,
    // 'pageHeaderImg' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'bgImage' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'breadcrumb' => [
    [
    'name' => 'Produk Digital',
    'url' => '#'
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

    <section class="review-one">
        <div class="container">
            <div class="comments-area">
                <div class="review-one__title">
                    <h3>2 reviews</h3>
                </div>
                <!--Start Comment Box-->
                <div class="comment-box">
                    <div class="comment">
                        <div class="author-thumb">
                            <figure class="thumb"><img src="assets/images/shop/review-1-1.jpg" alt="">
                            </figure>
                        </div>

                        <div class="review-one__content">
                            <div class="review-one__content-top">
                                <div class="info">
                                    <h2>Kevin martin <span>20 july 2025 . 4:00 pm</span></h2>
                                </div>
                                <div class="reply-btn">
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                </div>
                            </div>

                            <div class="review-one__content-bottom">
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting unchanged. It was popularised in the sheets containing lorem ipsum
                                    is simply free text. Class aptent taciti sociosqu ad litora torquent per conubia
                                    nostra, per inceptos himenaeos. Vestibulum sollicitudin varius mauris non
                                    dignissim.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End Comment Box-->

                <!--Start Comment Box-->
                <div class="comment-box">
                    <div class="comment">
                        <div class="author-thumb">
                            <figure class="thumb"><img src="assets/images/shop/review-1-2.jpg" alt="">
                            </figure>
                        </div>

                        <div class="review-one__content">
                            <div class="review-one__content-top">
                                <div class="info">
                                    <h2>Sarah albert <span>20 july 2025 . 4:00 pm</span></h2>
                                </div>
                                <div class="reply-btn">
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                </div>
                            </div>

                            <div class="review-one__content-bottom">
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting unchanged. It was popularised in the sheets containing lorem ipsum
                                    is simply free text. Class aptent taciti sociosqu ad litora torquent per conubia
                                    nostra, per inceptos himenaeos. Vestibulum sollicitudin varius mauris non
                                    dignissim.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End Comment Box-->
            </div>
        </div>
    </section>

    <section class="review-form-one">
        <div class="container">
            <div class="review-form-one__inner">
                <h3 class="review-form-one__title">Add a review</h3>
                <div class="review-form-one__rate-box">
                    <p class="review-form-one__rate-text">Rate this product?</p>
                    <div class="review-form-one__rate">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                    </div>
                </div>
                <form action="assets/inc/sendemail.php" class="review-form-one__form contact-form-validated"
                    novalidate="novalidate">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="review-form-one__input-box text-message-box">
                                <textarea name="message" placeholder="Write comment"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6">
                            <div class="review-form-one__input-box">
                                <input type="text" placeholder="Your name" name="name">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="review-form-one__input-box">
                                <input type="email" placeholder="Email address" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12">
                            <button type="submit" class="thm-btn review-form-one__btn"> <span class="icon-right"></span>
                                Submit comment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ env('MIDTRANS_SCRIPT_URL') }}" data-client-key="{{ env('MIDTRANS_CLIENTKEY') }}"></script>

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
