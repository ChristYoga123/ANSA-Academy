@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Lomba',
    'breadcrumb' => [
    [
    'name' => 'Lomba',
    'url' => route('lomba.index')
    ]
    ]
    ])
    <!--Events Page Start-->
    <section class="events-page">
        <div class="container">
            <div class="row">
                @forelse ($lombas as $lomba)
                    <!--Event One Single Start -->
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="event-one__single">
                            <div class="event-one__img">
                                <img src="{{ $lomba->getFirstMediaUrl('lomba-thumbnail') }}" alt="{{ $lomba->judul }}">
                                <div class="event-one__date">
                                    <p>{{ Carbon\Carbon::parse($lomba->waktu_mulai)->locale('id')->isoFormat('D') }}
                                    </p>
                                    <span>
                                        {{-- ambil Bulan --}}
                                        {{ Carbon\Carbon::parse($lomba->waktu_mulai)->locale('id')->isoFormat('MMMM') }}
                                    </span>
                                </div>
                            </div>
                            <div class="event-one__content">
                                <p class="event-one__time"> <span class="icon-clock"></span>
                                    {{ Carbon\Carbon::parse($lomba->waktu_mulai)->locale('id')->isoFormat('D MMMM YYYY') }}
                                    -
                                    {{ Carbon\Carbon::parse($lomba->waktu_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                                </p>
                                <h4 class="event-one__title"><a href="event-details.html">{{ $lomba->judul }}</a></h4>
                                <p class="blog-list__text"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-height: 3.6em;">
                                    {!! $lomba->deskripsi !!}
                                </p>
                                <div class="event-one__btn-box">
                                    <a href="{{ route('lomba.show', $lomba->slug) }}" class="thm-btn"><span
                                            class="icon-angles-right"></span>Baca Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Event One Single End -->

                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            <strong>Maaf!</strong> Belum ada lomba yang tersedia.
                        </div>
                    </div>
                @endforelse
                {{ $lombas->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>
    <!--Events Page End-->
@endsection
