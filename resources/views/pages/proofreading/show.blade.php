@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $proofreading->judul,
    'bgImage' => $proofreading->getFirstMediaUrl('program-thumbnail'),
    // 'pageHeaderImg' => $proofreading->getFirstMediaUrl('program-thumbnail'),
    'breadcrumb' => [
    [
    'name' => 'Proofreading',
    'url' => '#'
    ]
    ]
    ])
    <section class="course-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="course-details__left">
                        <div class="course-details__img">
                            <img src="{{ $proofreading->getFirstMediaUrl('program-thumbnail') }}"
                                alt="{{ $proofreading->judul }}" width="852px" height="400px">
                        </div>
                        <div class="course-details__content">
                            <div class="course-details__tag-box">
                                <div class="course-details__tag-shape"></div>
                                <span class="course-details__tag">Proofreading</span>
                            </div>
                            <h3 class="course-details__title">[Proofreading] - {{ $proofreading->judul }}</h3>
                            <div class="course-details__client-and-ratting-box">
                                <div class="course-details__client-box">
                                    <div class="course-details__client-img">
                                        <img src="{{ asset('assets/images/resources/course-details-client-img-1.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="course-details__client-content">
                                        <p>Pengecekan Oleh</p>
                                        <h4>Admin ANSA</h4>
                                    </div>
                                </div>
                                <div class="course-details__ratting-box-1">
                                    <ul class="course-details__ratting-list-1 list-unstyled">
                                        <li>
                                            <p>Tersedia Sejak:</p>
                                            <h4>
                                                {{ Carbon\Carbon::parse($proofreading->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                            </h4>
                                        </li>
                                        <li>
                                            <p>Paket</p>
                                            <h4>{{ $proofreading->proofreading_pakets_count }} Paket</h4>
                                        </li>
                                        <li>
                                            <p>({{ number_format($proofreading->testimoni_avg_rating, 1) }} / 5.0 Rating)
                                            </p>
                                            <ul class="course-details__ratting list-unstyled">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <li>
                                                        <i class="icon-star"
                                                            style="{{ $i < $proofreading->testimoni_avg_rating ? 'color: #ffd700;' : 'color: #ddd;' }}"></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="course-details__main-tab-box tabs-box">
                                <ul class="tab-buttons list-unstyled" style="border: 0;">
                                    <li data-tab="#overview" class="tab-btn active-btn tab-btn-one">
                                        <p><span class="icon-pen-ruler"></span>Informasi</p>
                                    </li>
                                    <li data-tab="#instructor" class="tab-btn tab-btn-three">
                                        <p><span class="icon-graduation-cap"></span>Mentor</p>
                                    </li>
                                    <li data-tab="#review" class="tab-btn tab-btn-four">
                                        <p><span class="icon-comments"></span>Ulasan</p>
                                    </li>
                                </ul>
                                <div class="tabs-content">
                                    <div class="tab active-tab" id="overview">
                                        <div class="course-details__tab-inner">
                                            <div class="course-details__overview">
                                                <h3 class="course-details__overview-title">Detail Proofreading</h3>
                                                <p class="course-details__overview-text-1">
                                                    {!! $proofreading->deskripsi !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab" id="instructor">
                                        <div class="course-details__tab-inner">
                                            <div class="course-details__Instructor mb-5">
                                                <div class="course-details__Instructor-img">
                                                    <img src="{{ $admin->getFirstMediaUrl('mentor-poster') }}"
                                                        alt="{{ $admin->name }}">
                                                </div>
                                                <div class="course-details__Instructor-content">
                                                    <div class="course-details__Instructor-client-name-box-and-view">
                                                        <div class="course-details__Instructor-client-name-box">
                                                            <h4>{{ $admin->name }}</h4>
                                                            <p>{{ $admin->custom_fields['bidang_mentor'] ?? 'Mentor Proofreading' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <ul class="course-details__Instructor-ratting-list list-unstyled">
                                                        <li>
                                                            <p><span class="fas fa-star"></span>(5.0 / 4.2 Rating)
                                                            </p>
                                                        </li>
                                                    </ul>
                                                    <div class="course-details__Instructor-social">
                                                        <a href="{{ $admin->custom_fields['linkedin'] ?? '#' }}"><span
                                                                class="fab fa-linkedin-in"></span></a>
                                                        <a href="{{ $admin->custom_fields['linkedin'] ?? '#' }}"><span
                                                                class="fab fa-instagram"></span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab" id="review">
                                        <div class="course-details__tab-inner">
                                            <section class="review-one">
                                                <div class="container">
                                                    <div class="comments-area">
                                                        <div class="review-one__title">
                                                            <h3>{{ $proofreading->testimoni_count ?? 0 }} Ulasan</h3>
                                                        </div>
                                                        @forelse ($proofreading->testimoni as $ulasan)
                                                            <!--Start Comment Box-->
                                                            <div class="comment-box">
                                                                <div class="comment">
                                                                    <div class="author-thumb">
                                                                        <figure class="thumb"><img
                                                                                src="{{ $ulasan->mentee?->getFirstMediaUrl('avatar_url') !== '' ? $ulasan->mentee->getFirstMediaUrl('avatar_url') : 'https://ui-avatars.com/api/?name=' . $ulasan->mentee->name }}"
                                                                                alt="{{ $ulasan->mentee->name }}"
                                                                                width="166px" height="166px">
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
                                                            <div class="alert alert-info">Belum ada ulasan untuk produk ini.
                                                            </div>
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
                                                            <form action="assets/inc/sendemail.php"
                                                                class="review-form-one__form contact-form-validated"
                                                                novalidate="novalidate">
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <div
                                                                            class="review-form-one__input-box text-message-box">
                                                                            <textarea name="message" placeholder="Ulasan"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        <button type="submit"
                                                                            class="thm-btn review-form-one__btn"> <span
                                                                                class="icon-right"></span>
                                                                            Kirim Ulasan</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </section>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="course-details__right">
                        <div class="course-details__info-box">
                            <div class="course-details__info-list">
                                <h3 class="course-details__info-list-title">Informasi Proofreading</h3>
                                <ul class="course-details__info-list-1 list-unstyled">
                                    <li>
                                        <p><i class="icon-book"></i>Paket</p>
                                        <span>{{ $proofreading->proofreading_pakets_count }}</span>
                                    </li>
                                    <li>
                                        <p><i class="icon-graduation-cap"></i>Checker</p>
                                        <span>Admin ANSA</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="form mt-4">
                                <div class="mb-4">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="icon-book me-2"></i>Pilih Paket*
                                    </label>
                                    <div class="banner-one__category-select-box">
                                        <div class="select-box">
                                            <select class="wide" name="paket" onchange="ubahHarga()">
                                                <option value="">Pilih Paket</option>
                                                @foreach ($proofreading->proofreadingPakets as $paket)
                                                    <option value="{{ $paket->id }}" data-harga="{{ $paket->harga }}"
                                                        data-min="{{ $paket->lembar_minimum }}"
                                                        data-max="{{ $paket->lembar_maksimum }}"
                                                        data-hari="{{ $paket->hari_pengerjaan }}">
                                                        {{ $paket->label }}
                                                        ({{ $paket->lembar_minimum }}-{{ $paket->lembar_maksimum }}
                                                        lembar, {{ $paket->hari_pengerjaan }} hari)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="paket-info" class="mb-4" style="display: none;">
                                    <div class="alert alert-info">
                                        <p class="mb-2"><strong>Informasi Paket:</strong></p>
                                        <p class="mb-1">Jumlah Lembar: <span id="lembar-range">-</span></p>
                                        <p class="mb-0">Estimasi Pengerjaan: <span id="hari-pengerjaan">-</span> hari
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="course-details__cuppon-box">
                                <label class="form-label d-flex align-items-center">
                                    <i class="icon-graduation-cap me-2"></i>Refferal Code/Kupon
                                </label>
                                <div class="course-details__search-form" style="margin-top: -2px">
                                    <input type="text" placeholder="Masukkan referral code/kupon"
                                        name="referral_code">
                                    <button type="submit" onclick="applyReferralCode()">Terapkan</button>

                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">Total Harga:</h5>
                                    <div class="fs-4 fw-bold" id="total-harga">Rp 0</div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <div class="course-details__doller-btn-box">
                                        <button type="button" class="thm-btn-two" onclick="beli()"
                                            style="background-color: white; border: 0;">
                                            <span>Daftar Sekarang</span>
                                            <i class="icon-angles-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <p class="text-center mt-3">*Setelah pembayaran berhasil, Anda akan diminta untuk
                                        mengunggah dokumen yang akan diperiksa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ env('MIDTRANS_SCRIPT_URL') }}" data-client-key="{{ env('MIDTRANS_CLIENTKEY') }}"></script>

    <script>
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
                    e.preventDefault();
                    @guest
                    toastr.error('Silahkan login terlebih dahulu untuk memberikan ulasan.');
                    return;
                @endguest

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
                    url: '{{ route('proofreading.testimoni', $proofreading->slug) }}',
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
        const paket = $('select[name="paket"]').val();
        if ($('#total-harga').text() === 'Rp 0') {
            toastr.error('Silahkan pilih paket terlebih dahulu.');
            return;
        }

        if (!referralCode) {
            // kembalikan harga ke harga awal
            const harga = $('select[name="paket"] option:selected').data('harga');
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
                paket: paket
            },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success('Referral code berhasil diterapkan.');
                    // coret harga dengan warna merah lalu tampilkan harga baru yaitu 5% dari harga awal
                    const harga = $('select[name="paket"] option:selected').data('harga');
                    const hargaDiskon = Math.floor(harga * 0.95);

                    $('#total-harga').html(
                        `<span style="text-decoration: line-through; color: red;">Rp ${harga}</span> Rp ${hargaDiskon}`
                    );

                } else {
                    // kembalikan harga ke harga awal
                    const harga = $('select[name="paket"] option:selected').data('harga');
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
                const harga = $('select[name="paket"] option:selected').data('harga');
                $('#total-harga').text(`Rp ${harga}`);
                toastr.error(errorMessage);
            }
        });
        }

        function ubahHarga() {
            const selectedOption = $('select[name="paket"] option:selected');
            const harga = selectedOption.data('harga');
            const minLembar = selectedOption.data('min');
            const maxLembar = selectedOption.data('max');
            const hariPengerjaan = selectedOption.data('hari');

            if (harga) {
                $('#total-harga').text(`Rp ${harga}`);
                $('#lembar-range').text(`${minLembar}-${maxLembar} lembar`);
                $('#hari-pengerjaan').text(hariPengerjaan);
                $('#paket-info').show();
            } else {
                $('#total-harga').text('Rp 0');
                $('#paket-info').hide();
            }
        }

        function beli() {
            @guest
            window.location.href = '{{ route('filament.mentee.auth.register') }}';

            return;
        @endguest
        $.ajax({
            url: `{{ route('proofreading.beli', $proofreading->slug) }}`,
            method: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
                referral_code: $('input[name="referral_code"]').val(),
                paket: $('select[name="paket"]').val()
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
        });
        }
    </script>
@endpush
