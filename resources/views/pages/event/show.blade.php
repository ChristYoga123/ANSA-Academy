@extends('layouts.app')

@section('content')
    @PageHeader([
    'bgImage' => $event->getFirstMediaUrl('event-thumbnail'),
    // 'pageHeaderImg' => $event->getFirstMediaUrl('event-thumbnail'),
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
                                                @include('components.mentor-card', ['mentor' => $mentor])
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
                                        @if (isset($activePromo))
                                            {{-- Jika ada promosi aktif, tampilkan harga yang dicoret dan harga setelah diskon --}}
                                            @php
                                                $hargaAsli = $event->harga;
                                                $persentaseDiskon = $activePromo->persentase;
                                                $hargaDiskon = $hargaAsli - ($hargaAsli * $persentaseDiskon) / 100;
                                            @endphp
                                            <h2 id="total-harga" data-harga="{{ $hargaDiskon }}">
                                                <span style="text-decoration: line-through; color: #999; font-size: 0.8em;">
                                                    Rp. {{ number_format($hargaAsli, 0, ',', '.') }}
                                                </span>
                                                <span style="color: #e74c3c; margin-left: 10px;">
                                                    Rp. {{ number_format($hargaDiskon, 0, ',', '.') }}
                                                </span>
                                                <span
                                                    style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 16px; margin-left: 5px;">
                                                    -{{ $persentaseDiskon }}%
                                                </span>
                                            </h2>
                                        @else
                                            {{-- Jika tidak ada promosi aktif, tampilkan harga normal --}}
                                            <h2 id="total-harga" data-harga="{{ $event->harga }}">
                                                Rp. {{ number_format($event->harga, 0, ',', '.') }}
                                            </h2>
                                        @endif
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
                            @if ($event->pricing === 'berbayar' && !isset($activePromo))
                                <div class="course-details__cuppon-box">
                                    <label class="form-label d-flex align-items-center">
                                        <i class="icon-graduation-cap me-2"></i>Referral Code/Kupon
                                    </label>
                                    <div class="course-details__search-form" style="margin-top: -2px">
                                        <input type="text" placeholder="Masukkan referral code/kupon"
                                            name="referral_code">
                                        <button type="submit" onclick="applyReferralCode()">Terapkan</button>
                                    </div>
                                </div>
                            @elseif($event->pricing === 'berbayar' && isset($activePromo))
                                <div class="alert alert-info mt-3">
                                    <i class="icon-info-circle me-2"></i>Diskon spesial sudah diterapkan.
                                </div>
                            @endif
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
                    // const hargaDiskon = Math.floor(harga * 0.95);
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
            url: `{{ route('event.beli', $event->slug) }}`,
            method: 'POST',
            data: {
                _token: `{{ csrf_token() }}`,
                referral_code: $('input[name="referral_code"]').val()
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
                        window.location.href = '{{ route('pembayaran.sukses', '') }}/' + response.transaksi_id;
                    }
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
