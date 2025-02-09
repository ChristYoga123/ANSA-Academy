@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $kelasAnsa->judul,
    'bgImage' => $kelasAnsa->getFirstMediaUrl('program-thumbnail'),
    // 'pageHeaderImg' => $kelasAnsa->getFirstMediaUrl('program-thumbnail'),
    'breadcrumb' => [
    [
    'name' => 'Kelas ANSA',
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
                            <img src="{{ $kelasAnsa->getFirstMediaUrl('program-thumbnail') }}" alt="{{ $kelasAnsa->judul }}"
                                width="852px" height="400px">
                        </div>
                        <div class="course-details__content">
                            <div class="course-details__tag-box">
                                <div class="course-details__tag-shape"></div>
                                <span class="course-details__tag">Kelas ANSA</span>
                            </div>
                            <h3 class="course-details__title">[Kelas] - {{ $kelasAnsa->judul }}</h3>
                            <div class="course-details__client-and-ratting-box">
                                <div class="course-details__client-box">
                                    <div class="course-details__client-content">
                                        <p>Kuota Peserta</p>
                                        <h4>{{ $kelasAnsa->kelasAnsaDetail->kuota }} Peserta</h4>
                                    </div>
                                </div>
                                <div class="course-details__ratting-box-1">
                                    <ul class="course-details__ratting-list-1 list-unstyled">
                                        <li>
                                            <p>Periode Kelas</p>
                                            <h4>
                                                {{-- hitung waktu mulai - waktu selesai = --}}
                                                {{ Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_mulai)->diffInDays(Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_selesai)) + 1 }}
                                                Hari
                                            </h4>
                                        </li>
                                        <li>
                                            <p>({{ number_format($kelasAnsa->testimoni_avg_rating, 1) }} / 5.0 Rating)</p>
                                            <ul class="course-details__ratting list-unstyled">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <li>
                                                        <i class="icon-star"
                                                            style="{{ $i < $kelasAnsa->testimoni_avg_rating ? 'color: #ffd700;' : 'color: #ddd;' }}"></i>
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
                                                <h3 class="course-details__overview-title">Detail Kelas</h3>
                                                <p class="course-details__overview-text-1">
                                                    {!! $kelasAnsa->deskripsi !!}
                                                </p>
                                                <div class="mt-4">
                                                    <h4>Jadwal Pendaftaran:</h4>
                                                    <ul class="list-unstyled mt-4">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="icon-calendar me-2"></i>
                                                            <span>Mulai:
                                                                {{ Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_open_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }}</span>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <i class="icon-calendar me-2"></i>
                                                            <span>Berakhir:
                                                                {{ Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_close_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab" id="instructor">
                                        <div class="course-details__tab-inner">
                                            @foreach ($kelasAnsa->mentors as $mentor)
                                                @include('components.mentor-card', ['mentor' => $mentor])
                                            @endforeach
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
                                                        @forelse ($kelasAnsa->testimoni as $ulasan)
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
                                                            <form class="review-form-one__form contact-form-validated"
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
                                <h3 class="course-details__info-list-title">Informasi Kelas</h3>
                                <ul class="course-details__info-list-1 list-unstyled">
                                    <li>
                                        <p><i class="icon-calendar"></i>Periode</p>
                                        <span>{{ Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_mulai)->locale('id')->isoFormat('D MMM') }}
                                            -
                                            {{ Carbon\Carbon::parse($kelasAnsa->kelasAnsaDetail->waktu_selesai)->locale('id')->isoFormat('D MMM Y') }}</span>
                                    </li>
                                    <li>
                                        <p><i class="icon-clock"></i>Pendaftaran</p>
                                        <div class="event-details__list-right">
                                            @php
                                                $now = now();
                                                $status = 'Ditutup';
                                                $badgeClass = 'bg-danger text-white';

                                                if (
                                                    $now->between(
                                                        $kelasAnsa->kelasAnsaDetail->waktu_open_registrasi,
                                                        $kelasAnsa->kelasAnsaDetail->waktu_close_registrasi,
                                                        true,
                                                    )
                                                ) {
                                                    $status = 'Dibuka';
                                                    $badgeClass = 'bg-success text-white';
                                                } elseif (
                                                    $now->lt($kelasAnsa->kelasAnsaDetail->waktu_open_registrasi)
                                                ) {
                                                    $status = 'Segera Dibuka';
                                                    $badgeClass = 'bg-warning text-white';
                                                }
                                            @endphp
                                            <span class="p-1 rounded {{ $badgeClass }}">{{ $status }}</span>
                                        </div>
                                    </li>
                                    <li>
                                        <p><i class="icon-stamp"></i>Kuota</p>
                                        <span>{{ $kelasAnsa->kelasAnsaDetail->kuota }} Peserta</span>
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
                                                @foreach ($kelasAnsa->kelasAnsaPakets as $paket)
                                                    <option value="{{ $paket->id }}"
                                                        data-harga="{{ $paket->harga }}">
                                                        {{ $paket->label }} ({{ $paket->harga }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="course-details__cuppon-box">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="icon-tag me-2"></i>Referral Code
                                    </label>
                                    <div class="course-details__search-form" style="margin-top: -2px">
                                        <input type="text" placeholder="Masukkan referral code" name="referral_code">
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
                    url: `{{ route('kelas-ansa.testimoni', $kelasAnsa->slug) }}`,
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

        function ubahHarga() {
            const paket = document.querySelector('select[name="paket"]');
            const harga = paket.options[paket.selectedIndex].dataset.harga;
            document.getElementById('total-harga').innerText = `Rp ${harga}`;
        }

        function beli() {
            @guest
            toastr.error('Silahkan login terlebih dahulu untuk mendaftar kelas.');
            return;
        @endguest
        $.ajax({
            url: `{{ route('kelas-ansa.beli', $kelasAnsa->slug) }}`,
            data: {
                _token: '{{ csrf_token() }}',
                paket: $('select[name="paket"]').val(),
                referral_code: $('input[name="referral_code"]').val() ?? null
            },
            method: 'POST',
            beforeSend: function() {
                $('button[onclick="beli()"]').attr('disabled', true);
            },
            success: function(response) {
                $('button[onclick="beli()"]').attr('disabled', false);

                if (response.status === 'success' && response.snap_token) {
                    snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '{{ route('pembayaran.sukses') }}';
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
