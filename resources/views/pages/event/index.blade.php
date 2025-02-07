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
                                <h4 class="event-one__title"><a
                                        href="{{ route('event.show', $event->slug) }}">{{ $event->judul }}</a></h4>
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
