@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $mentoring->judul,
    'bgImage' => $mentoring->getFirstMediaUrl('program-thumbnail'),
    // 'pageHeaderImg' => $mentoring->getFirstMediaUrl('program-thumbnail'),
    'breadcrumb' => [
    [
    'name' => 'Mentoring',
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
                            <img src="{{ $mentoring->getFirstMediaUrl('program-thumbnail') }}" alt="{{ $mentoring->judul }}"
                                width="852px" height="400px">
                        </div>
                        <div class="course-details__content">
                            <div class="course-details__tag-box">
                                <div class="course-details__tag-shape"></div>
                                <span class="course-details__tag">Mentoring</span>
                            </div>
                            <h3 class="course-details__title">[Mentoring] - {{ $mentoring->judul }}</h3>
                            <div class="course-details__client-and-ratting-box">
                                <div class="course-details__client-box">
                                    <div class="course-details__client-img">
                                        <img src="{{ asset('assets/images/resources/course-details-client-img-1.jpg') }}"
                                            alt="">
                                    </div>
                                    <div class="course-details__client-content">
                                        <p>Jumlah Mentor</p>
                                        <h4>{{ $mentoring->mentors_count }} Mentor</h4>
                                    </div>
                                </div>
                                <div class="course-details__ratting-box-1">
                                    <ul class="course-details__ratting-list-1 list-unstyled">
                                        <li>
                                            <p>Dibuat Pada:</p>
                                            <h4>
                                                {{ Carbon\Carbon::parse($mentoring->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                            </h4>
                                        </li>
                                        <li>
                                            <p>Paket</p>
                                            <h4>{{ $mentoring->mentoring_pakets_count }} Paket</h4>
                                        </li>
                                        <li>
                                            <p>({{ number_format($mentoring->testimoni_avg_rating, 1) }} / 5 Rating)</p>
                                            <ul class="course-details__ratting list-unstyled">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <li>
                                                        <i class="icon-star"
                                                            style="{{ $i < $mentoring->testimoni_avg_rating ? 'color: #ffd700;' : 'color: #ddd;' }}"></i>
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
                                                <h3 class="course-details__overview-title">Detail Mentoring</h3>
                                                <p class="course-details__overview-text-1">
                                                    {!! $mentoring->deskripsi !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Tab-->
                                    <div class="tab" id="instructor">
                                        <div class="course-details__tab-inner">
                                            @foreach ($mentoring->mentors as $mentor)
                                                <div class="course-details__Instructor mb-5">
                                                    <div class="course-details__Instructor-img">
                                                        <img src="{{ $mentor->getFirstMediaUrl('mentor-poster') }}"
                                                            alt="{{ $mentor->name }}">
                                                    </div>
                                                    <div class="course-details__Instructor-content">
                                                        <div class="course-details__Instructor-client-name-box-and-view">
                                                            <div class="course-details__Instructor-client-name-box">
                                                                <h4>{{ $mentor->name }}</h4>
                                                                <p>{{ $mentor->custom_fields['bidang_mentor'] ?? 'Mentor' }}
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
                                                            <a href="{{ $mentor->custom_fields['linkedin'] ?? '#' }}"><span
                                                                    class="fab fa-linkedin-in"></span></a>
                                                            <a href="{{ $mentor->custom_fields['linkedin'] ?? '#' }}"><span
                                                                    class="fab fa-instagram"></span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!--Tab-->
                                    <div class="tab " id="review">
                                        <section class="review-one">
                                            <div class="container">
                                                <div class="comments-area">
                                                    <div class="review-one__title">
                                                        <h3>{{ $mentoring->testimoni_count ?? 0 }} Ulasan</h3>
                                                    </div>
                                                    @forelse ($mentoring->testimoni as $ulasan)
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
                                                                <div class="review-form-one__input-box text-message-box">
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
                                    </div>
                                    <!--Tab-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="course-details__right">
                        <div class="course-details__info-box">
                            <div class="course-details__info-list">
                                <h3 class="course-details__info-list-title">Informasi Mentoring</h3>
                                <ul class="course-details__info-list-1 list-unstyled">
                                    <li>
                                        <p><i class="icon-book"></i>Paket</p>
                                        <span>{{ $mentoring->mentoring_pakets_count }}</span>
                                    </li>
                                    <li>
                                        <p><i class="icon-graduation-cap"></i>Mentor</p>
                                        <span>{{ $mentoring->mentors_count }}</span>
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
                                                @foreach ($mentoring->mentoringPakets as $paket)
                                                    <option value="{{ $paket->id }}" data-harga={{ $paket->harga }}>
                                                        {{ $paket->jenis }}-{{ $paket->label }} ({{ $paket->harga }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label d-flex align-items-center">
                                        <i class="icon-graduation-cap me-2"></i>Pilih Mentor*
                                    </label>
                                    <div class="banner-one__category-select-box">
                                        <div class="select-box">
                                            <select class="wide" name="mentor">
                                                <option value="">Pilih Mentor</option>
                                                @foreach ($mentoring->mentors as $mentor)
                                                    <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="course-details__cuppon-box">
                                <label class="form-label d-flex align-items-center">
                                    <i class="icon-graduation-cap me-2"></i>Refferal Code
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
                                            style="
                                            background-color: white;
                                            border: 0;
                                            ">
                                            <span>Enroll Now</span>
                                            <i class="icon-angles-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    {{-- berikan keterangan jika membeli paket Lanjutan, ketika pembelajaran berlangsung wajib melampirkan file dokumen yang akan dipakai saat mentoring --}}
                                    <p class="text-center mt-3">*Pembelian paket Lanjutan wajib melampirkan file dokumen
                                        yang akan dipakai saat mentoring</p>
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
                    url: '{{ route('mentoring.testimoni', $mentoring->slug) }}',
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
            const harga = $('select[name="paket"] option:selected').data('harga');
            if (harga) {
                $('#total-harga').text(`Rp ${harga}`);
            } else {
                $('#total-harga').text('Rp 0');
            }
        }

        function beli() {
            @guest
            toastr.error('Silahkan login terlebih dahulu untuk membeli paket.');
            return;
        @endguest
        $.ajax({
            url: `{{ route('mentoring.beli', $mentoring->slug) }}`,
            method: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
                referral_code: $('input[name="referral_code"]').val(),
                paket: $('select[name="paket"]').val(),
                mentor: $('select[name="mentor"]').val()
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
