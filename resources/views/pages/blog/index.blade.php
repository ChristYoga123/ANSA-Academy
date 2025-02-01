@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Artikel',
    'breadcrumb' => [
    [
    'name' => 'Artikel',
    'url' => route('blog.index')
    ]
    ],
    ])

    <!--Blog Page Start-->
    <section class="blog-page">
        <div class="container">
            <div class="row">
                @forelse ($artikels as $artikel)
                    <!--Blog Two Single Start -->
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="blog-two__single">
                            <div class="blog-two__img">
                                <img src="{{ $artikel->getFirstMediaUrl('artikel-thumbnail') }}" alt="{{ $artikel->judul }}"
                                    width="414px" height="260px">
                                <div class="blog-two__date">
                                    <span class="icon-calendar"></span>
                                    <p>
                                        {{ Carbon\Carbon::parse($artikel->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="blog-two__content">
                                <h4 class="blog-two__title"><a
                                        href="{{ route('blog.show', $artikel->slug) }}">{{ $artikel->judul }}</a></h4>
                                <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-height: 3.6em;"
                                    class="blog-two__text">{!! $artikel->deskripsi !!}</p>
                            </div>
                        </div>
                    </div>
                    <!--Blog Two Single End -->

                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <strong>Maaf!</strong> Belum ada artikel yang tersedia.
                        </div>
                    </div>
                @endforelse
                {{ $artikels->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>
    <!--Blog Page End-->
@endsection
