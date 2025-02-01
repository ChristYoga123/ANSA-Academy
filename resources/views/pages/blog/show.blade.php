@extends('layouts.app')

@section('content')
    @PageHeader([
    'bgImage' => $artikel->getFirstMediaUrl('artikel-thumbnail'),
    'pageTitle' => $artikel->judul,
    'pageHeaderImg' => $artikel->getFirstMediaUrl('artikel-thumbnail'),
    'breadcrumb' => [
    [
    'name' => $artikel->judul,
    'url' => route('blog.show', $artikel->slug)
    ]
    ]
    ])
    <!--Blog Details Start-->
    <section class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-details__left">
                        <div class="blog-details__img-box">
                            <div class="blog-details__img">
                                <img src="{{ $artikel->getFirstMediaUrl('artikel-thumbnail') }}" alt="">
                            </div>
                        </div>
                        <div class="blog-details__content">
                            <h3 class="blog-details__title-1">{{ $artikel->judul }}</h3>
                            <div class="blog-details__client-and-meta">
                                <div class="blog-details__client-box">
                                    <div class="blog-details__client-img">
                                        <img src="https://ui-avatars.com/api/?name=Admin" alt="user">
                                    </div>
                                    <div class="blog-details__client-content">
                                        <p>Dipublish Oleh</p>
                                        <h4>Admin</h4>
                                    </div>
                                </div>
                                <ul class="blog-details__client-meta list-unstyled">
                                    <li>
                                        <div class="icon">
                                            <span class="icon-calendar"></span>
                                        </div>
                                        <p>
                                            {{ Carbon\Carbon::parse($artikel->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <p class="blog-details__text-1">
                                {!! $artikel->deskripsi !!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="sidebar">
                        <div class="sidebar__single sidebar__search">
                            <div class="sidebar__title-box">
                                <div class="sidebar__title-icon">
                                    <img src="{{ asset('assets/images/icon/sidebar-title-icon.png') }}" alt="">
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
                        {{-- <div class="sidebar__single sidebar__category">
                            <div class="sidebar__title-box">
                                <div class="sidebar__title-icon">
                                    <img src="assets/images/icon/sidebar-title-icon.png" alt="">
                                </div>
                                <h3 class="sidebar__title">Category </h3>
                            </div>
                            <ul class="sidebar__category-list list-unstyled">
                                <li>
                                    <a href="blog-details.html">Digital Marketing - (45)<span
                                            class="fas fa-arrow-right"></span></a>
                                </li>
                                <li>
                                    <a href="blog-details.html">Health & Fitness - (12)<span
                                            class="fas fa-arrow-right"></span></a>
                                </li>
                                <li class="active">
                                    <a href="blog-details.html">Programming & Tech - (78)<span
                                            class="fas fa-arrow-right"></span></a>
                                </li>
                                <li>
                                    <a href="blog-details.html">Product Design - (45)<span
                                            class="fas fa-arrow-right"></span></a>
                                </li>
                                <li>
                                    <a href="blog-details.html">Online Chef - (12)<span
                                            class="fas fa-arrow-right"></span></a>
                                </li>
                            </ul>
                        </div>
                        <div class="sidebar__single sidebar__post">
                            <div class="sidebar__title-box">
                                <div class="sidebar__title-icon">
                                    <img src="assets/images/icon/sidebar-title-icon.png" alt="">
                                </div>
                                <h3 class="sidebar__title">Artikel Terkait </h3>
                            </div>
                            <ul class="sidebar__post-list list-unstyled">
                                <li>
                                    <div class="sidebar__post-image">
                                        <img src="assets/images/blog/blog-lp-1.jpg" alt="">
                                    </div>
                                    <div class="sidebar__post-content">
                                        <ul class="sidebar__post-meta list-unstyled">
                                            <li>
                                                <p><span class="icon-tags"></span>Development</p>
                                            </li>
                                            <li>
                                                <p><span class="icon-clock"></span>10 Min Read</p>
                                            </li>
                                        </ul>
                                        <h3 class="sidebar__post-title"><a href="blog-details.html">Creating a
                                                Productive Study Space for Online Learning</a></h3>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Blog Details End-->
@endsection
