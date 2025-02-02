@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Proofreading',
    'breadcrumb' => [
    [
    'name' => 'Proofreading',
    'url' => route('proofreading.index')
    ]
    ]
    ])

    <section class="course-grid" style="margin-bottom: -30px">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="course-grid__left">
                        <div class="course-grid__sidebar">
                            <div class="course-grid__search course-grid__single">
                                <div class="course-grid__title-box">
                                    <h3 class="course-grid__title">Cari</h3>
                                    <div class="course-grid__title-shape-1">
                                        <img src="{{ asset('assets/images/shapes/course-grid-title-shape-1.png') }}"
                                            alt="">
                                    </div>
                                </div>
                                <p class="course-grid__search-text">With the release of Letraset sheets containi
                                    Lorem Ipsum passages</p>
                                <form action="{{ route('proofreading.search') }}" method="POST">
                                    @csrf
                                    <input type="search" placeholder="Cari Mentoring" name="search">
                                    <button type="submit"><i class="icon-search"></i>Search</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="course-grid__right">
                        <div class="course-grid__right-content-box">
                            <div class="row">
                                @forelse ($proofreadings as $proofreading)
                                    <div class="col-xl-6">
                                        <div class="courses-two__single">
                                            <div class="courses-two__img-box">
                                                <div
                                                    style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                                    {{ $proofreading->program }}
                                                </div>
                                                <div class="courses-two__img">
                                                    <img src="{{ $proofreading->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                                        alt="{{ $proofreading->judul }}" width="368px" height="230px">
                                                </div>
                                                <div class="courses-two__heart">
                                                    <a href="#"><span class="icon-heart"></span></a>
                                                </div>
                                            </div>
                                            <div class="courses-two__content">
                                                <div class="courses-two__doller-and-review">
                                                    <div class="courses-two__doller">
                                                        <p>Mulai
                                                            dari
                                                            {{ 'Rp ' . number_format($proofreading->proofreadingPakets->min('harga'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <h3 class="courses-two__title">
                                                    <a
                                                        href="{{ route('proofreading.show', $proofreading->slug) }}">{{ $proofreading->judul }}</a>
                                                </h3>
                                                <div class="courses-two__btn-and-client-box">
                                                    <div class="courses-two__btn-box">
                                                        <a href="{{ route('proofreading.show', $proofreading->slug) }}"
                                                            class="thm-btn-two">
                                                            <span>Daftar Sekarang</span>
                                                            <i class="icon-angles-right"></i>
                                                        </a>
                                                    </div>
                                                    <div class="courses-two__client-box">
                                                        <div class="courses-two__client-content">
                                                            <h4>Jumlah Mentor</h4>
                                                            <p>1 Mentor</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="courses-two__meta list-unstyled">
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-book"></span>
                                                        </div>
                                                        <p>{{ $proofreading->proofreading_pakets_count }} Paket</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xl-12">
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Maaf!</strong> Tidak ada proofreading yang tersedia.
                                        </div>
                                    </div>
                                @endforelse
                                <!--Courses Two Single End-->
                            </div>
                        </div>
                        {{ $proofreadings->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
