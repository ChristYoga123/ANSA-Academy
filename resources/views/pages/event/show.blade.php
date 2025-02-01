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
    [
    'name' => $event->judul,
    'url' => route('event.show', $event->slug)
    ]
    ],
    ])

    <!--Event Details Start-->
    <section class="event-details">
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
                            <h3 class="event-details__content-title">Tentang Event</h3>
                            <p class="event-details__content-text-1" style="margin-top: -30px">{!! $event->deskripsi !!}</p>
                            <h3 class="event-details__content-title" style="margin-top: 30px">Pendaftaran Event</h3>
                            <ul class="list-unstyled event-details__point">
                                <li>
                                    <div class="icon">
                                        <span class="icon-angles-right"></span>
                                    </div>
                                    <div class="content">
                                        <h5>Buka Pendaftaran</h5>
                                        <p>
                                            {{ Carbon\Carbon::parse($event->waktu_open_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <span class="icon-angles-right"></span>
                                    </div>
                                    <div class="content">
                                        <h5>Tutup Pendaftaran</h5>
                                        <p>
                                            {{ Carbon\Carbon::parse($event->waktu_close_registrasi)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                        </p>
                                    </div>
                            </ul>
                            <h3 class="event-details__content-title" style="margin-top: 30px">Jadwal Event</h3>
                            <ul class="list-unstyled event-details__point">
                                @foreach ($event->eventJadwals as $jadwal)
                                    <li>
                                        <div class="icon">
                                            <span class="icon-angles-right"></span>
                                        </div>
                                        <div class="content">
                                            <h5>{{ $jadwal->jadwal }}</h5>
                                            <p>
                                                {{ Carbon\Carbon::parse($jadwal->waktu)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
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
                                        <h2>
                                            Rp. {{ number_format($event->harga, 0, ',', '.') }}
                                        </h2>
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
                                            } elseif ($now->lt($event->waktu_open_regis)) {
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
                                        <p>{{ $event->mentees_count ?? 0 }}/{{ $event->kuota }}</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="event-details__btn-box">
                                <a href="#" class="thm-btn"><span class="icon-angles-right"></span>Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($event->tipe === 'offline')
                <div class="event-details__location-box" style="margin-bottom: 50px">
                    <h4 class="event-details__location-title">Lokasi Event</h4>
                    <div class="row">
                        <div class="col-xl-12">
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
                    </div>
                </div>
            @else
                <div class="event-details__online-box" style="margin-bottom: 50px">
                    <h4 class="event-details__location-title" style="margin: 30px 0 20px 0">Informasi Meeting Online</h4>
                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="event-details__online-info">
                                <div class="alert alert-info">
                                    <h5><i class="icon-video-camera"></i> Meeting Online</h5>
                                    <p>Event ini akan dilaksanakan secara online melalui platform meeting yang akan
                                        diinformasikan setelah Anda melakukan registrasi.</p>
                                </div>
                                <div class="event-details__online-notes">
                                    <h5 style="margin: 30px 0 20px 0">Catatan Penting:</h5>
                                    <ul>
                                        <li>Link meeting akan dikirimkan ke email Anda setelah registrasi berhasil</li>
                                        <li>Pastikan perangkat Anda mendukung untuk meeting online</li>
                                        <li>Siapkan koneksi internet yang stabil</li>
                                        <li>Bergabunglah ke meeting 10 menit sebelum acara dimulai</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5">
                            <div class="event-details__online-requirements">
                                <h4 class="event-details__location-venue-title">Persyaratan Teknis</h4>
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
                </div>
            @endif
        </div>
    </section>
    <!--Event Details End-->
@endsection
