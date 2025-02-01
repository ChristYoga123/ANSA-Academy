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

    <section class="blog-list">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-list__left">
                        @forelse ($artikels as $artikel)
                            <div class="blog-list__single">
                                <div class="blog-list__img-box">
                                    <div class="blog-list__img">
                                        <img src="{{ $artikel->getFirstMediaUrl('artikel-thumbnail') }}"
                                            alt="{{ $artikel->judul }}" width="400" height="450">
                                    </div>
                                    <div class="blog-list__date">
                                        <p><span class="icon-calendar"></span>
                                            {{ Carbon\Carbon::parse($artikel->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="blog-list__content">
                                    <div class="blog-list__client-and-meta">
                                        <div class="blog-list__client-box">
                                            <div class="blog-list__client-img">
                                                <img src="{{ asset('assets/images/blog/blog-list-client-img-1.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="blog-list__client-content">
                                                <p>Diterbitkan Oleh</p>
                                                <h4>Admin</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="blog-list__title-1"><a
                                            href="{{ route('blog.show', $artikel->slug) }}">{{ $artikel->judul }}
                                        </a></h3>
                                    <p class="blog-list__text"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; max-height: 3.6em;">
                                        {!! $artikel->deskripsi !!}
                                    </p>
                                    <div class="blog-list__btn-box">
                                        <a href="{{ route('blog.show', $artikel->slug) }}" class="thm-btn-two">
                                            <span>Baca Detail</span>
                                            <i class="icon-angles-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="blog-list__single">
                                <div class="blog-list__content">
                                    <h3 class="blog-list__title-1">Belum ada artikel</h3>
                                </div>
                            </div>
                        @endforelse
                        {{ $artikels->links('vendor.pagination.custom') }}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="sidebar">
                        <div class="sidebar__single sidebar__search">
                            <div class="sidebar__title-box">
                                <div class="sidebar__title-icon">
                                    <img src="assets/images/icon/sidebar-title-icon.png" alt="">
                                </div>
                                <h3 class="sidebar__title">Cari </h3>
                            </div>
                            <p class="sidebar__search-text">Search blogs to discover a vast world of online content
                                on countless topics.</p>
                            <form action="{{ route('blog.search') }}" class="sidebar__search-form" method="POST">
                                @csrf
                                <input type="search" placeholder="Search.." name="search">
                                <button type="submit"><i class="icon-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
