@extends('layouts.app')

@section('content')
    @PageHeader([
    'bgImage' => $event->getFirstMediaUrl('event-thumbnail'),
    'pageHeaderImg' => $event->getFirstMediaUrl('event-thumbnail'),
    'pageTitle' => $event->judul,
    'breadcrumb' => [
    [
    'name' => 'Event',
    'url' => route('event.index')
    ],
    ],
    ])

    <section class="event-details" style="margin-bottom: 100px;">
        <div class="container">
            <div class="event-details__top">
                <div class="event-details__top-img">
                    <img src="{{ $event->getFirstMediaUrl('event-thumbnail') }}" alt="{{ $event->judul }}" width="1290px"
                        height="590px">
                </div>
            </div>
            <div class="event-details__content-box">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="event-details__content-left">
                            <div class="course-details__main-tab-box tabs-box">
                                <ul class="tab-buttons list-unstyled" style="border: 0;">
                                    <li data-tab="#overview" class="tab-btn active-btn tab-btn-one">
                                        <p><span class="icon-pen-ruler"></span>Informasi</p>
                                    </li>
                                    <li data-tab="#schedule" class="tab-btn tab-btn-two">
                                        <p><span class="icon-calendar"></span>Jadwal</p>
                                    </li>
                                    <li data-tab="#instructor" class="tab-btn tab-btn-three">
                                        <p><span class="icon-graduation-cap"></span>Mentor</p>
                                    </li>
                                    <li data-tab="#venue" class="tab-btn tab-btn-four">
                                        <p><span class="icon-globe"></span>Venue</p>
                                    </li>
                                </ul>

                                <div class="tabs-content">
                                    <!-- Informasi Tab -->
                                    <div class="tab active-tab" id="overview">
                                        <div class="course-details__tab-inner">
                                            <div class="course-details__overview">
                                                <h3 class="course-details__overview-title">Tentang Event</h3>
                                                <p class="course-details__overview-text-1">{!! $event->deskripsi !!}</p>

                                                <h3 class="course-details__overview-title mt-4">Pendaftaran Event</h3>
                                                <ul class="list-unstyled event-details__point">
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-angles-right"></span>
                                                        </div>
                                                        <div class="content">
                                                            <h5>Buka Pendaftaran</h5>
                                                            <p>{{ Carbon\Carbon::parse($event->waktu_open_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                                            </p>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-angles-right"></span>
                                                        </div>
                                                        <div class="content">
                                                            <h5>Tutup Pendaftaran</h5>
                                                            <p>{{ Carbon\Carbon::parse($event->waktu_close_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                                            </p>
                                                        </div>
                                                    </li>
                                                </ul>

                                                @if ($event->link_resource)
                                                    <h3 class="course-details__overview-title mt-4">Benefit</h3>
                                                    <p class="course-details__overview-text-1">
                                                        Terdapat beberapa benefit yang bisa Anda unduh melalui dashboard
                                                        setelah melakukan transaksi.
                                                        Jangan lewatkan kesempatan untuk mendapatkan benefit menarik dari
                                                        event ini.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Jadwal Tab -->
                                    <div class="tab" id="schedule">
                                        <div class="course-details__tab-inner">
                                            <div class="course-details__overview">
                                                <h3 class="course-details__overview-title">Jadwal Event</h3>
                                                <ul class="list-unstyled event-details__point">
                                                    @foreach ($event->eventJadwals as $jadwal)
                                                        <li>
                                                            <div class="icon">
                                                                <span class="icon-angles-right"></span>
                                                            </div>
                                                            <div class="content">
                                                                <h5>{{ $jadwal->jadwal }}</h5>
                                                                <p>{{ Carbon\Carbon::parse($jadwal->waktu)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                                                </p>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mentor Tab -->
                                    <div class="tab" id="instructor">
                                        <div class="course-details__tab-inner">
                                            @foreach ($event->mentors as $mentor)
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
                                                                <p><span class="fas fa-star"></span>(5.0 / 4.2 Rating)</p>
                                                            </li>
                                                        </ul>
                                                        <div class="course-details__Instructor-social">
                                                            <a href="{{ $mentor->custom_fields['linkedin'] ?? '#' }}"><span
                                                                    class="fab fa-linkedin-in"></span></a>
                                                            <a href="{{ $mentor->custom_fields['instagram'] ?? '#' }}"><span
                                                                    class="fab fa-instagram"></span></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Venue Tab -->
                                    <div class="tab" id="venue">
                                        <div class="course-details__tab-inner">
                                            @if ($event->tipe === 'offline')
                                                <div class="event-details__location-box">
                                                    <h4 class="event-details__location-title">Lokasi Event</h4>
                                                    <div class="event-details__location-venue">
                                                        <ul class="list-unstyled event-details__location-venue-list">
                                                            <li>
                                                                <div class="event-details__location-venue-left">
                                                                    <p>Venue</p>
                                                                </div>
                                                                <div class="event-details__location-venue-right">
                                                                    <p>{{ $event->venue }}</p>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="event-details__online-box">
                                                    <h4 class="event-details__location-title">Informasi Meeting Online</h4>
                                                    <div class="event-details__online-info">
                                                        <div class="alert alert-info">
                                                            <h5><i class="icon-video-camera"></i> Meeting Online</h5>
                                                            <p>Event ini akan dilaksanakan secara online melalui platform
                                                                meeting yang akan
                                                                diinformasikan setelah Anda melakukan registrasi.</p>
                                                        </div>
                                                        <div class="event-details__online-notes">
                                                            <h5 class="mt-4">Catatan Penting:</h5>
                                                            <ul>
                                                                <li>Link meeting akan dikirimkan ke email Anda setelah
                                                                    registrasi berhasil</li>
                                                                <li>Pastikan perangkat Anda mendukung untuk meeting online
                                                                </li>
                                                                <li>Siapkan koneksi internet yang stabil</li>
                                                                <li>Bergabunglah ke meeting 10 menit sebelum acara dimulai
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="event-details__online-requirements mt-4">
                                                            <h4>Persyaratan Teknis</h4>
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <i class="icon-check"></i>
                                                                    <span>Laptop/PC/Smartphone</span>
                                                                </li>
                                                                <li>
                                                                    <i class="icon-check"></i>
                                                                    <span>Mikrofon</span>
                                                                </li>
                                                                <li>
                                                                    <i class="icon-check"></i>
                                                                    <span>Kamera (opsional)</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="event-details__sidebar">
                            <h3 class="event-details__sidebar-title">Informasi Event</h3>
                            <ul class="list-unstyled event-details__list">
                                <li>
                                    <div class="event-details__list-left">
                                        <div class="event-details__list-icon">
                                            <span class="icon-catagory"></span>
                                        </div>
                                        <p class="event-details__list-text">Harga:</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        <h2>Rp. {{ number_format($event->harga, 0, ',', '.') }}</h2>
                                    </div>
                                </li>
                                <li>
                                    <div class="event-details__list-left">
                                        <div class="event-details__list-icon">
                                            <span class="icon-calendar"></span>
                                        </div>
                                        <p class="event-details__list-text">Pendaftaran:</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        @php
                                            $now = now();
                                            $status = 'Ditutup';
                                            $badgeClass = 'bg-danger text-white';

                                            if (
                                                $now->between(
                                                    $event->waktu_open_registrasi,
                                                    $event->waktu_close_registrasi,
                                                    true,
                                                )
                                            ) {
                                                $status = 'Dibuka';
                                                $badgeClass = 'bg-success text-white';
                                            } elseif ($now->lt($event->waktu_open_registrasi)) {
                                                $status = 'Segera Dibuka';
                                                $badgeClass = 'bg-warning text-white';
                                            }
                                        @endphp
                                        <span class="p-1 rounded {{ $badgeClass }}">{{ $status }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="event-details__list-left">
                                        <div class="event-details__list-icon">
                                            <span class="icon-stamp"></span>
                                        </div>
                                        <p class="event-details__list-text">Slot:</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        <p>{{ $event->transaksi_count ?? 0 }}/{{ $event->kuota }}</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="event-details__btn-box">
                                <button class="thm-btn" onclick="beli()" style="border: 0"><span
                                        class="icon-angles-right"></span>Daftar
                                    Sekarang</button>
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
        function beli() {
            @guest
            window.location.href = `{{ route('filament.mentee.auth.login') }}`
            return;
        @endguest
        $.ajax({
            url: `{{ route('event.beli', $event->slug) }}`,
            method: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
            },
            beforeSend: function() {
                $('button[onclick="beli()"]').attr('disabled', true);
            },
            success: function(response) {
                $('button[onclick="beli()"]').attr('disabled', false);

                if (response.status === 'success') {
                    if (response.snap_token) {
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
                    }
                    toastr.success(response.message);
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
