@extends('layouts.app')

@section('content')
    @PageHeader([
    'bgImage' => $lomba->getFirstMediaUrl('lomba-thumbnail'),
    'pageHeaderImg' => $lomba->getFirstMediaUrl('lomba-thumbnail'),
    'pageTitle' => $lomba->judul,
    'breadcrumb' => [
    [
    'name' => $lomba->judul,
    'url' => route('lomba.show', $lomba->slug)
    ]
    ]
    ])
    <!--Event Details Start-->
    <section class="event-details" style="margin-bottom: 120px">
        <div class="container">
            <div class="event-details__top">
                <div class="event-details__top-img">
                    <img src="{{ $lomba->getFirstMediaUrl('lomba-thumbnail') }}" alt="{{ $lomba->judul }}" width="1290"
                        height="590">
                </div>
            </div>
            <div class="event-details__content-box">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="event-details__content-left">
                            <h3 class="event-details__content-title">Tentang Lomba</h3>
                            <p class="event-details__content-text-1">{!! $lomba->deskripsi !!}</p>

                            <!-- Jadwal Section -->
                            <h3 class="event-details__content-title" style="margin-top: 40px;">Jadwal Kegiatan</h3>

                            <!-- Pendaftaran -->
                            <div class="event-details__content-text-1" style="margin-bottom: 25px;">
                                <h4 style="font-size: 20px; margin-bottom: 15px;">
                                    <span class="icon-calendar"
                                        style="margin-right: 10px; color: var(--thm-primary);"></span>
                                    Pendaftaran
                                </h4>
                                <div style="padding-left: 35px;">
                                    <p style="margin-bottom: 5px;">
                                        <strong>Mulai:</strong>
                                        {{ Carbon\Carbon::parse($lomba->waktu_open_registrasi)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                                    </p>
                                    <p style="margin-bottom: 10px;">
                                        <strong>Selesai:</strong>
                                        {{ Carbon\Carbon::parse($lomba->waktu_close_registrasi)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                                    </p>
                                    @if (Carbon\Carbon::now()->between($lomba->waktu_open_registrasi, $lomba->waktu_close_registrasi))
                                        <span style="color: #28a745; font-weight: 500;">Sedang Berlangsung</span>
                                    @elseif(Carbon\Carbon::now()->lt($lomba->waktu_open_registrasi))
                                        <span style="color: #ffc107; font-weight: 500;">Belum Dibuka</span>
                                    @else
                                        <span style="color: #dc3545; font-weight: 500;">Sudah Ditutup</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Pelaksanaan -->
                            <div class="event-details__content-text-1">
                                <h4 style="font-size: 20px; margin-bottom: 15px;">
                                    <span class="icon-clock" style="margin-right: 10px; color: var(--thm-primary);"></span>
                                    Pelaksanaan Acara
                                </h4>
                                <div style="padding-left: 35px;">
                                    <p style="margin-bottom: 5px;">
                                        <strong>Mulai:</strong>
                                        {{ Carbon\Carbon::parse($lomba->waktu_mulai)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                                    </p>
                                    @if ($lomba->waktu_selesai)
                                        <p style="margin-bottom: 10px;">
                                            <strong>Selesai:</strong>
                                            {{ Carbon\Carbon::parse($lomba->waktu_selesai)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}
                                        </p>
                                    @endif
                                    @if (Carbon\Carbon::now()->between($lomba->waktu_mulai, $lomba->waktu_selesai ?? ''))
                                        <span style="color: #28a745; font-weight: 500;">Sedang Berlangsung</span>
                                    @elseif(Carbon\Carbon::now()->lt($lomba->waktu_mulai))
                                        <span style="color: #ffc107; font-weight: 500;">Belum Dimulai</span>
                                    @else
                                        <span style="color: #dc3545; font-weight: 500;">Sudah Selesai</span>
                                    @endif
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
                                            <span class="icon-calendar"></span>
                                        </div>
                                        <p class="event-details__list-text">Registrasi :</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        @if (Carbon\Carbon::now()->between($lomba->waktu_open_registrasi, $lomba->waktu_close_registrasi))
                                            <p class="text-success badge">Sedang Berlangsung</p>
                                        @elseif(Carbon\Carbon::now()->lt($lomba->waktu_open_registrasi))
                                            <p class="text-warning badge">Belum Dibuka</p>
                                        @elseif(Carbon\Carbon::now()->gt($lomba->waktu_close_registrasi))
                                            <p class="text-danger badge">Sudah Ditutup</p>
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="event-details__list-left">
                                        <div class="event-details__list-icon">
                                            <span class="icon-clock"></span>
                                        </div>
                                        <p class="event-details__list-text">Acara :</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        @if (Carbon\Carbon::now()->between($lomba->waktu_mulai, $lomba->waktu_selesai ?? ''))
                                            <p class="text-success badge">Sedang Berlangsung</p>
                                        @elseif(Carbon\Carbon::now()->lt($lomba->waktu_mulai))
                                            <p class="text-warning badge">Belum Dibuka</p>
                                        @elseif(Carbon\Carbon::now()->gt($lomba->waktu_selesai))
                                            <p class="text-danger badge">Sudah Ditutup</p>
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="event-details__list-left">
                                        <div class="event-details__list-icon">
                                            <span class="icon-stamp"></span>
                                        </div>
                                        <p class="event-details__list-text">Penyelenggara:</p>
                                    </div>
                                    <div class="event-details__list-right">
                                        <p>{{ $lomba->penyelenggara }}</p>
                                    </div>
                                </li>
                            </ul>
                            <div class="event-details__btn-box">
                                @if (Carbon\Carbon::now()->between($lomba->waktu_open_registrasi, $lomba->waktu_close_registrasi))
                                    <a href="{{ $lomba->link_pendaftaran }}" target="_blank" class="thm-btn"><span
                                            class="icon-angles-right"></span>Daftar Sekarang</a>
                                @elseif(Carbon\Carbon::now()->lt($lomba->waktu_open_registrasi))
                                    <a class="thm-btn" disabled><span class="icon-angles-right"></span>Belum
                                        Dibuka</a>
                                @elseif(Carbon\Carbon::now()->gt($lomba->waktu_close_registrasi))
                                    <a class="thm-btn" disabled><span class="icon-angles-right"></span>Sudah
                                        Ditutup</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Event Details End-->
@endsection
