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
                                            <p>(5.0 / 4.2 Rating)</p>
                                            <ul class="course-details__ratting list-unstyled">
                                                <li><span class="icon-star"></span></li>
                                                <li><span class="icon-star"></span></li>
                                                <li><span class="icon-star"></span></li>
                                                <li><span class="icon-star"></span></li>
                                                <li><span class="icon-star"></span></li>
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
                                            <div class="comment-form">
                                                <h3 class="comment-form__title">Masukkan Ulasan</h3>
                                                <div class="comment-form__text-and-ratting">
                                                    <p class="comment-form__text">Berikan Rating </p>
                                                    <ul class="comment-form__ratting list-unstyled">
                                                        <li><span class="icon-star"></span></li>
                                                        <li><span class="icon-star"></span></li>
                                                        <li><span class="icon-star"></span></li>
                                                        <li><span class="icon-star"></span></li>
                                                        <li><span class="icon-star"></span></li>
                                                    </ul>
                                                </div>
                                                <form action="assets/inc/sendemail.php"
                                                    class="comment-form__form contact-form-validated"
                                                    novalidate="novalidate">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="comment-form__input-box text-message-box">
                                                                <textarea name="message" placeholder="Write Review"></textarea>
                                                            </div>
                                                            <div class="comment-form__btn-box">
                                                                <button type="submit" class="comment-form__btn">
                                                                    <span class="icon-arrow-circle"></span>Kirim Ulasan
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="result"></div>
                                            </div>
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
            window.location.href = `{{ route('filament.mentee.auth.login') }}`
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
        });
        }
    </script>
@endpush
