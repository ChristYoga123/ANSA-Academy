@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Mentoring',
    'breadcrumb' => [
    [
    'name' => 'Mentoring',
    'url' => route('mentoring.index')
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
                                <form action="{{ route('mentoring.search') }}" method="POST">
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
                                <!--Courses Two Single Start-->
                                {{-- <div class="col-xl-6">
                                    <div class="courses-two__single">
                                        <div class="courses-two__img-box">
                                            <div class="courses-two__img">
                                                <img src="{{ asset('assets/images/resources/courses-2-1.jpg') }}"
                                                    alt="">
                                            </div>
                                            <div class="courses-two__heart">
                                                <a href="course-details.html"><span class="icon-heart"></span></a>
                                            </div>
                                        </div>
                                        <div class="courses-two__content">
                                            <div class="courses-two__doller-and-review">
                                                <div class="courses-two__doller">
                                                    <p>$240.00</p>
                                                </div>
                                                <div class="courses-two__review">
                                                    <p><i class="icon-star"></i> 4.5 <span>(129 Reviews)</span></p>
                                                </div>
                                            </div>
                                            <h3 class="courses-two__title"><a href="course-details.html">Getting
                                                    Started with
                                                    Computers and Beginner's Guide to Basic Skills</a></h3>
                                            <div class="courses-two__btn-and-client-box">
                                                <div class="courses-two__btn-box">
                                                    <a href="course-details.html" class="thm-btn-two">
                                                        <span>Enroll Now</span>
                                                        <i class="icon-angles-right"></i>
                                                    </a>
                                                </div>
                                                <div class="courses-two__client-box">
                                                    <div class="courses-two__client-img">
                                                        <img src="{{ asset('assets/images/resources/courses-two-client-img-1.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="courses-two__client-content">
                                                        <h4>Sarah Alison</h4>
                                                        <p><span class="odometer" data-count="12">00</span><i>+</i>
                                                            Years Experian</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="courses-two__meta list-unstyled">
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-chart-simple"></span>
                                                    </div>
                                                    <p>Beginner</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-book"></span>
                                                    </div>
                                                    <p>45 Lesson</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-clock"></span>
                                                    </div>
                                                    <p>620h, 55min</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> --}}
                                @forelse ($mentorings as $mentoring)
                                    <div class="col-xl-6">
                                        <div class="courses-two__single">
                                            <div class="courses-two__img-box">
                                                <div
                                                    style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                                    {{ $mentoring->program }}
                                                </div>
                                                <div class="courses-two__img">
                                                    <img src="{{ $mentoring->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                                        alt="{{ $mentoring->judul }}" width="368px" height="230px">
                                                </div>
                                                <div class="courses-two__heart">
                                                    <a href="{{ route('mentoring.show', $mentoring->slug) }}"><span
                                                            class="icon-heart"></span></a>
                                                </div>
                                            </div>
                                            <div class="courses-two__content">
                                                <div class="courses-two__doller-and-review">
                                                    <div class="courses-two__doller">
                                                        <p>Mulai dari
                                                            {{ 'Rp ' . number_format($mentoring->mentoringPakets->min('harga'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <h3 class="courses-two__title">
                                                    <a href="{{ route('mentoring.show', $mentoring->slug) }}">[Mentoring]
                                                        {{ $mentoring->judul }}</a>
                                                </h3>
                                                <div class="courses-two__btn-and-client-box">
                                                    <div class="courses-two__btn-box">
                                                        <a href="{{ route('mentoring.show', $mentoring->slug) }}"
                                                            class="thm-btn-two">
                                                            <span>Daftar Sekarang</span>
                                                            <i class="icon-angles-right"></i>
                                                        </a>
                                                    </div>
                                                    <div class="courses-two__client-box">
                                                        <div class="courses-two__client-content">
                                                            <h4>Jumlah Mentor</h4>
                                                            <p>{{ $mentoring->mentors_count }} Mentor</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="courses-two__meta list-unstyled">
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-book"></span>
                                                        </div>
                                                        <p>{{ $mentoring->mentoring_pakets_count }} Paket
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xl-12">
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Maaf!</strong> Tidak ada mentoring yang tersedia.
                                        </div>
                                    </div>
                                @endforelse
                                <!--Courses Two Single End-->
                            </div>
                        </div>
                        {{ $mentorings->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
