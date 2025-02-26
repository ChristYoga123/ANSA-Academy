@extends('layouts.app')

@push('styles')
    <style>
        /* Add these styles to your CSS */
        .review-form-one__rate .icon-star {
            cursor: pointer;
            transition: color 0.2s ease;
            color: #ddd;
        }

        .review-form-one__rate .icon-star.active {
            color: #ffd700;
        }

        /* Optional: Add hover effect for better UX */
        .review-form-one__rate .icon-star:hover {
            transform: scale(1.1);
        }
    </style>
@endpush

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
                            {{ $produkDigital->judul }}
                            @if (isset($activePromo))
                                <div class="d-flex align-items-center">
                                    <del class="text-danger me-3">
                                        Rp. {{ number_format($produkDigital->harga, 0, ',', '.') }}
                                    </del>
                                    <span id="total-harga"
                                        data-harga="{{ $produkDigital->harga - ($produkDigital->harga * $activePromo->persentase) / 100 }}">
                                        Rp.
                                        {{ number_format($produkDigital->harga - ($produkDigital->harga * $activePromo->persentase) / 100, 0, ',', '.') }}
                                    </span>
                                    <span class="badge bg-danger ms-3 text-white">Diskon
                                        {{ $activePromo->persentase }}%</span>
                                </div>
                            @else
                                <span id="total-harga" data-harga="{{ $produkDigital->harga }}">
                                    Rp. {{ number_format($produkDigital->harga, 0, ',', '.') }}
                                </span>
                            @endif
                        </h3>
                    </div>
                    (Dipublish oleh {{ $produkDigital?->mentor?->name ?? 'Mentor' }})
                    <div class="product-details__reveiw">
                        <span class="p-1 rounded bg-primary text-white">Platform: {{ $produkDigital->platform }}</span>
                    </div>
                    <div class="product-details__content">
                        <p class="product-details__content-text1">{!! $produkDigital->deskripsi !!}</p>
                        <strong class="product-details__content-text2">Stok:
                            {{ $produkDigital->is_unlimited ? 'Tidak Terbatas' : $produkDigital->qty }} <br>
                            <span
                                class="text-{{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'success' : 'danger' }}">
                                {{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </strong>
                    </div>
                    <!-- Add Referral Code Input -->
                    @if (!isset($activePromo))
                        <div class="product-details__buttons mt-4" style="margin-bottom: -40px; width: 100%;">
                            <div class="course-details__search-form">
                                <input type="text" placeholder="Masukkan referral code/kupon" name="referral_code">
                                <button type="submit" onclick="applyReferralCode()">Terapkan</button>
                            </div>
                        </div>
                    @endif
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
                    <h3>{{ $produkDigital->testimoni_count ?? 0 }} Ulasan</h3>
                </div>
                @forelse ($produkDigital->testimoni as $ulasan)
                    <!--Start Comment Box-->
                    <div class="comment-box">
                        <div class="comment">
                            <div class="author-thumb">
                                <figure class="thumb"><img
                                        src="{{ $ulasan->mentee?->getFirstMediaUrl('avatar_url') !== '' ? $ulasan->mentee->getFirstMediaUrl('avatar_url') : 'https://ui-avatars.com/api/?name=' . $ulasan->mentee->name }}"
                                        alt="{{ $ulasan->mentee->name }}" width="166px" height="166px">
                                </figure>
                            </div>

                            <div class="review-one__content">
                                <div class="review-one__content-top">
                                    <div class="info">
                                        <h2>{{ $ulasan->mentee->name }} <span>
                                                {{ Carbon\Carbon::parse($ulasan->created_at)->locale('id')->diffForHumans() }}
                                            </span></h2>
                                    </div>
                                    <div class="reply-btn">
                                        @for ($i = 0; $i < $ulasan->rating; $i++)
                                            <i class="icon-star active"></i>
                                        @endfor
                                    </div>
                                </div>

                                <div class="review-one__content-bottom">
                                    <p>{{ $ulasan->ulasan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Comment Box-->
                @empty
                    <div class="alert alert-info">Belum ada ulasan untuk produk ini.</div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($canGiveTestimoni)
        <section class="review-form-one">
            <div class="container">
                <div class="review-form-one__inner">
                    <h3 class="review-form-one__title">Ulasan</h3>
                    <div class="review-form-one__rate-box">
                        <p class="review-form-one__rate-text">Masukkan rating</p>
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
                                    <textarea name="message" placeholder="Ulasan"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <button type="submit" class="thm-btn review-form-one__btn"> <span
                                        class="icon-right"></span>
                                    Kirim Ulasan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script src="{{ env('MIDTRANS_SCRIPT_URL') }}" data-client-key="{{ env('MIDTRANS_CLIENTKEY') }}"></script>

    <script>
        // Add this to your scripts section
        document.addEventListener('DOMContentLoaded', function() {
            // Star rating functionality
            const starContainer = document.querySelector('.review-form-one__rate');
            const stars = starContainer.querySelectorAll('.icon-star');
            let selectedRating = 0;

            // Set initial inline styles
            stars.forEach(star => {
                star.style.cursor = 'pointer';
                star.style.transition = 'color 0.2s ease, transform 0.2s ease';
                star.style.color = '#ddd';
                star.style.marginRight = '5px';
            });

            // Add hover and click effects to stars
            stars.forEach((star, index) => {
                // Hover effects
                star.addEventListener('mouseenter', () => {
                    updateStars(index);
                });

                starContainer.addEventListener('mouseleave', () => {
                    updateStars(selectedRating - 1);
                });

                // Click handling
                star.addEventListener('click', () => {
                    selectedRating = index + 1;
                    updateStars(index);
                });
            });

            // Function to update stars visual
            function updateStars(activeIndex) {
                stars.forEach((star, index) => {
                    if (index <= activeIndex) {
                        star.style.color = '#ffd700';
                        star.style.transform = 'scale(1.1)';
                    } else {
                        star.style.color = '#ddd';
                        star.style.transform = 'scale(1)';
                    }
                });
            }

            // Form submission handler using jQuery Ajax
            $('.review-form-one__form').on('submit', function(e) {
                    @guest
                    toastr.error('Silahkan login terlebih dahulu untuk memberikan ulasan.');
                    return;
                @endguest
                e.preventDefault();

                if (selectedRating === 0) {
                    toastr.error('Wajib memberikan rating bintang.');
                    return;
                }

                const $form = $(this);
                const formData = {
                    rating: selectedRating,
                    comment: $form.find('textarea[name="message"]').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('produk-digital.testimoni', $produkDigital->slug) }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $form.find('button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success('Berhasil menambahkan review');
                            // Reset form
                            $form[0].reset();
                            selectedRating = 0;
                            stars.forEach(star => {
                                star.style.color = '#ddd';
                                star.style.transform = 'scale(1)';
                            });
                        } else {
                            toastr.error(response.message ||
                                'Terjadi kesalahan. Mohon coba lagi.');
                        }
                    },
                    error: function(xhr) {
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
                    },
                    complete: function() {
                        $form.find('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        });

        function applyReferralCode() {
            @guest
            window.location.href = '{{ route('filament.mentee.auth.register') }}';

            return;
        @endguest
        const referralCode = $('input[name="referral_code"]').val();

        if (!referralCode) {
            // kembalikan harga ke harga awal
            const harga = $('#total-harga').data('harga');
            $('#total-harga').text(`Rp ${harga}`);
            toastr.error('Silahkan masukkan referral code.');
            return;
        }
        $.ajax({
            url: `{{ route('check-referral-code') }}`,
            method: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
                referral_code: referralCode,
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success('Referral code berhasil diterapkan.');
                    // coret harga dengan warna merah lalu tampilkan harga baru yaitu 5% dari harga awal
                    const harga = $('#total-harga').data('harga');
                    const hargaDiskon = response.tipe === 'referral' ? Math.floor(harga *
                        0.95) : Math.floor(harga - (harga * response.persentase / 100));

                    $('#total-harga').html(
                        `<span style="text-decoration: line-through; color: red;">Rp ${harga}</span> Rp ${hargaDiskon}`
                    );

                } else {
                    // kembalikan harga ke harga awal
                    const harga = $('#total-harga').data('harga');
                    $('#total-harga').text(`Rp ${harga}`);
                    toastr.error(response.message || 'Referral code tidak valid.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan. Mohon coba lagi.';
                try {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
                // kembalikan harga ke harga awal
                const harga = $('#total-harga').data('harga');
                $('#total-harga').text(`Rp ${harga}`);
                toastr.error(errorMessage);
            }
        });
        }

        function beli() {
            @guest
            window.location.href = '{{ route('filament.mentee.auth.register') }}';

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
                            window.location.href = '{{ route('pembayaran.sukses', '') }}/' +
                                response.transaksi_id;
                        },
                        onPending: function(result) {
                            window.location.href = '{{ route('pembayaran.gagal') }}';
                        },
                        onError: function(result) {
                            window.location.href = '{{ route('pembayaran.gagal') }}';
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
