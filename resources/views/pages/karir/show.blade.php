@extends('layouts.app')

@section('content')
    <section class="become-a-teacher">
        <div class="container">
            <div class="become-a-teacher__top">
                <div class="section-title-two text-center sec-title-animation animation-style1">
                    <div class="section-title-two__tagline-box">
                        <div class="section-title-two__tagline-shape">
                            <img src="{{ asset('assets/images/shapes/section-title-two-shape-1.png') }}" alt="">
                        </div>
                        <span class="section-title-two__tagline">Karir</span>
                    </div>
                    <h2 class="section-title-two__title title-animation">Daftar Sebagai
                        <span>Mentor</span>
                    </h2>
                </div>
                <div class="become-a-teacher__tab-box tabs-box">
                    <ul class="tab-buttons clearfix list-unstyled">
                        <li data-tab="#jobdesk-utama" class="tab-btn active-btn"><span>Jobdesk Utama</span></li>
                        <li data-tab="#kualifikasi" class="tab-btn"><span>Kualifikasi</span></li>
                        <li data-tab="#benefit" class="tab-btn"><span>Benefit</span></li>
                    </ul>
                    <div class="tabs-content">
                        <div class="tab active-tab" id="jobdesk-utama">
                            <div class="become-a-teacher__content">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Melakukan mentoring 101 (private) kepada mentee ANSA Academy mengenai pemilihan
                                        mahasiswa berprestasi, lomba esai, PKM-RE, PKM-PM dan bisnis case untuk siswa (SMP
                                        dan SMA) dan mahasiswa
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Memberikan review dan arahan kepada mente terkait dengan penulisan, pembuatan
                                        ide/gagasan, metode penelitian/penulisan, dan analisis gagasan
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Melakukan pelaporan mentoring kepada tim manajemen ANSA Academy
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Membantu dalam memberikan inovasi SOP pembelajaran di ANSA Academy
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab" id="kualifikasi">
                            <div class="become-a-teacher__content">
                                <div class="qualification-section mb-4">
                                    <ul class="list-unstyled">
                                        @foreach ($lokerMentorBidang->lokerMentorBidangKualifikasi as $kualifikasi)
                                            <li class="mb-2"><i class="fas fa-check-circle me-2"></i>
                                                {{ $kualifikasi->kualifikasi }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab" id="benefit">
                            <div class="become-a-teacher__content">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Project fee mentor menyesuaikan dengan waktu bimbingan
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Fee lain seperti mengantarkan mentee finalis dan juara
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Fee Pembuatan e-book di ANSA Academy
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Mendapatkan upgrading keilmuan mengenai karya ilmiah
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Memiliki pengalaman dibidang mentoring keilmiahan
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-gift me-2"></i>
                                        Mendapatkan relasi baru
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="become-a-teacher__bottom">
                <div class="row">
                    <div class="col-xl-4">
                        <div class="become-a-teacher__img-box">
                            <div class="become-a-teacher__img">
                                <img src="{{ asset('assets/images/resources/become-a-teacher-img-1.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="become-a-teacher__right">
                            <div class="section-title-two text-left sec-title-animation animation-style1">
                                <h2 class="section-title-two__title title-animation">Formulir Pendaftaran Mentor</h2>
                            </div>
                            <form class="contact-form-validated contact-three__form"
                                action="{{ route('karir.store', $lokerMentorBidang->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Nama Lengkap*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                                class="@error('nama') is-invalid @enderror">
                                            @error('nama')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Email*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="email" name="email" value="{{ old('email') }}" required
                                                class="@error('email') is-invalid @enderror">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">No. HP (Mulai dari 628xxxxxx)*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                                class="@error('no_hp') is-invalid @enderror" placeholder="628xxxxxx">
                                            @error('no_hp')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Universitas*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="universitas" value="{{ old('universitas') }}"
                                                required class="@error('universitas') is-invalid @enderror">
                                            @error('universitas')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Semester*</h4>
                                        <div class="contact-three__input-box">
                                            <select name="semester" required
                                                class="form-select @error('semester') is-invalid @enderror">
                                                <option value="">Pilih Semester</option>
                                                @foreach (['6', '7', '8', '9', 'Fresh Graduate'] as $semester)
                                                    <option value="{{ $semester }}"
                                                        {{ old('semester') == $semester ? 'selected' : '' }}>
                                                        {{ $semester }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('semester')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Mahasiswa Berprestasi</h4>
                                        <div class="contact-three__input-box">
                                            <select name="mahasiswa_berprestrasi"
                                                class="form-select @error('mahasiswa_berprestrasi') is-invalid @enderror">
                                                <option value="">Pilih Level Prestasi</option>
                                                @foreach (['Fakultas', 'Universitas', 'Wilayah', 'Nasional'] as $level)
                                                    <option value="{{ $level }}"
                                                        {{ old('mahasiswa_berprestrasi') == $level ? 'selected' : '' }}>
                                                        {{ $level }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('mahasiswa_berprestrasi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">LinkedIn*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="linkedin" value="{{ old('linkedin') }}" required
                                                class="@error('linkedin') is-invalid @enderror">
                                            @error('linkedin')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Instagram*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="instagram" value="{{ old('instagram') }}"
                                                required class="@error('instagram') is-invalid @enderror">
                                            @error('instagram')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <h4 class="contact-three__input-title">Alasan Mendaftar*</h4>
                                        <div class="contact-three__input-box text-message-box">
                                            <textarea name="alasan_mendaftar" required class="@error('alasan_mendaftar') is-invalid @enderror">{{ old('alasan_mendaftar') }}</textarea>
                                            @error('alasan_mendaftar')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <h4 class="contact-three__input-title">Pencapaian*</h4>
                                        <div class="row">
                                            @for ($i = 0; $i < 5; $i++)
                                                <div class="col-12 mb-3">
                                                    <div class="contact-three__input-box">
                                                        <input type="text" name="pencapaian[]"
                                                            placeholder="Pencapaian {{ $i + 1 }}"
                                                            value="{{ old('pencapaian.' . $i) }}" required
                                                            class="@error('pencapaian.' . $i) is-invalid @enderror">
                                                        @error('pencapaian.' . $i)
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Link Drive Portofolio*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="drive_portofolio"
                                                value="{{ old('drive_portofolio') }}" required
                                                class="@error('drive_portofolio') is-invalid @enderror">
                                            @error('drive_portofolio')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6">
                                        <h4 class="contact-three__input-title">Link Drive CV*</h4>
                                        <div class="contact-three__input-box">
                                            <input type="text" name="drive_cv" value="{{ old('drive_cv') }}" required
                                                class="@error('drive_cv') is-invalid @enderror">
                                            @error('drive_cv')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="contact-three__btn-box">
                                            <button type="submit" class="thm-btn-two contact-three__btn">
                                                <span>Daftar Sebagai Mentor</span>
                                                <i class="icon-angles-right"></i>
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
    </section>
@endsection

<style>
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .alert {
        position: relative;
        padding: 1rem 1rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }

    .alert ul {
        list-style-type: none;
        padding-left: 0;
    }
</style>

@push('scripts')
    <script>
        $(document).ready(function() {
            const form = $('.contact-form-validated');
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();

            // Remove the contact-form-validated class to prevent potential duplicate handlers
            form.removeClass('contact-form-validated');

            // Single form submission handler
            form.off('submit').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Prevent double submission
                if (submitBtn.prop('disabled')) {
                    return false;
                }

                // Disable form and show loading state
                submitBtn.prop('disabled', true);
                submitBtn.html('<span>Processing...</span> <i class="fas fa-spinner fa-spin"></i>');

                const formData = new FormData(this);
                const url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message, 'Success');
                            form[0].reset();
                            // reset select input
                            form.find('select').val('');
                            // Clear validation states
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');
                        } else {
                            toastr.error('Unexpected response from server', 'Error');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Clear previous errors
                            $('.invalid-feedback').remove();
                            $('.is-invalid').removeClass('is-invalid');

                            // Show new errors
                            Object.keys(errors).forEach(function(field) {
                                const input = $(`[name="${field}"]`);
                                input.addClass('is-invalid');

                                if (field.includes('pencapaian.')) {
                                    const index = field.split('.')[1];
                                    input.after(
                                        `<span class="invalid-feedback">${errors[field][0]}</span>`
                                    );
                                } else {
                                    input.after(
                                        `<span class="invalid-feedback">${errors[field][0]}</span>`
                                    );
                                }
                            });

                            toastr.error(
                                'Terdapat kesalahan saat mengisi form. Harap periksa kembali!',
                                'Error');
                        } else {
                            toastr.error('Data gagal disimpan', 'Error');
                        }
                    },
                    complete: function() {
                        // Re-enable form
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalBtnText);
                    }
                });

                return false; // Extra measure to prevent form submission
            });
        });
    </script>
@endpush
