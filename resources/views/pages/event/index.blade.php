@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Event',
    'pageHeaderImg' => $webResource->getFirstMediaUrl('event_banner'),
    'breadcrumb' => [
    [
    'name' => 'Event',
    'url' => route('event.index')
    ]
    ],
    ])
    <!--Events Page Start-->
    <section class="events-page">
        <div class="container">
            <div class="row">
                <!--Event One Single Start -->
                @forelse ($events as $event)
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="event-one__single">
                            <div class="event-one__img">
                                <img src="{{ $event->getFirstMediaUrl('event-thumbnail') }}" alt="{{ $event->judul }}"
                                    width="414px" height="275px">
                                <div class="event-one__date">
                                    <p>
                                        {{ $event->event_jadwals_count }}
                                    </p>
                                    <span>
                                        Jadwal
                                    </span>
                                </div>
                            </div>
                            <div class="event-one__content">
                                <p class="event-one__time"> <span class="icon-clock"></span> Mulai dari
                                    {{ Carbon\Carbon::parse($event->eventJadwals->first()->waktu)->locale('id')->format('d M Y') }}
                                </p>
                                <h4 class="event-one__title">
                                    <a href="{{ route('event.show', $event->slug) }}">{{ $event->judul }}</a>
                                </h4>

                                {{-- Tampilkan harga dengan diskon jika ada --}}
                                @php
                                    $hargaAsli = $event->harga;
                                    $diskonPersen = null;

                                    // Cek apakah ada promosi per produk
                                    if (isset($promoProducts[$event->id])) {
                                        $diskonPersen = $promoProducts[$event->id];
                                    }
                                    // Jika tidak ada promosi per produk, cek promosi kategori
                                    elseif (isset($promoKategori)) {
                                        $diskonPersen = $promoKategori->persentase;
                                    }

                                    $hargaDiskon = $diskonPersen
                                        ? $hargaAsli - ($hargaAsli * $diskonPersen) / 100
                                        : null;
                                @endphp

                                <div class="event-one__price">
                                    @if ($diskonPersen)
                                        <span class="event-one__price-original"
                                            style="text-decoration: line-through; color: #999;">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                        <span class="event-one__price-discount"
                                            style="color: #e74c3c; font-weight: bold; margin-left: 10px;">
                                            Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                                        </span>
                                        <span class="event-one__discount-badge"
                                            style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; margin-left: 5px;">
                                            -{{ $diskonPersen }}%
                                        </span>
                                    @else
                                        <span class="event-one__price-normal">
                                            Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="event-one__location">
                                    <div class="event-one__location-icon">
                                        <span class="icon-location"></span>
                                    </div>
                                    <p class="event-one__loation-text">
                                        {{ $event->tipe === 'offline' ? $event->venue : 'Online Meeting' }}
                                    </p>
                                </div>
                                <div class="event-one__btn-box">
                                    <a href="{{ route('event.show', $event->slug) }}" class="thm-btn"><span
                                            class="icon-angles-right"></span>Baca Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="alert alert-warning">
                            <strong>Maaf!</strong> Belum ada event yang tersedia.
                        </div>
                    </div>
                @endforelse
                {{ $events->links('vendor.pagination.custom') }}
                <!--Event One Single End -->
            </div>
        </div>
    </section>
    <!--Events Page End-->
@endsection
