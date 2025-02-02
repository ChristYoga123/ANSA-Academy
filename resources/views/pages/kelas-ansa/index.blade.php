@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Kelas ANSA',
    'breadcrumb' => [
    [
    'name' => 'Kelas ANSA',
    'url' => route('kelas-ansa.index')
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
                                <form action="{{ route('kelas-ansa.search') }}" method="POST">
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
                                @forelse ($kelas as $kel)
                                    <div class="col-xl-6">
                                        <div class="courses-two__single">
                                            <div class="courses-two__img-box">
                                                <div
                                                    style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                                    {{ $kel->program }}
                                                </div>
                                                <div class="courses-two__img">
                                                    <img src="{{ $kel->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                                        alt="{{ $kel->judul }}" width="368px" height="230px">
                                                </div>
                                                <div class="courses-two__heart">
                                                    <a href="#"><span class="icon-heart"></span></a>
                                                </div>
                                            </div>
                                            <div class="courses-two__content">
                                                <div class="courses-two__doller-and-review">
                                                    <div class="courses-two__doller">
                                                        <p>Mulai dari
                                                            {{ 'Rp ' . number_format($kel->kelasAnsaPakets->min('harga'), 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <h3 class="courses-two__title">
                                                    <a
                                                        href="{{ route('kelas-ansa.show', $kel->slug) }}">{{ $kel->judul }}</a>
                                                </h3>
                                                <div class="courses-two__btn-and-client-box">
                                                    <div class="courses-two__btn-box">
                                                        <a href="{{ route('kelas-ansa.show', $kel->slug) }}"
                                                            class="thm-btn-two">
                                                            <span>Daftar Sekarang</span>
                                                            <i class="icon-angles-right"></i>
                                                        </a>
                                                    </div>
                                                    <div class="courses-two__client-box">
                                                        <div class="courses-two__client-content">
                                                            <h4>Jumlah Mentor</h4>
                                                            <p>{{ $kel->mentors_count }} Mentor</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="courses-two__meta list-unstyled">
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-calendar"></span>
                                                        </div>
                                                        <p>Mulai:
                                                            {{ $kel->kelasAnsaDetail->waktu_mulai->format('d M Y') }}
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-user-plus"></span>
                                                        </div>
                                                        <p>Kuota: {{ $kel->kelasAnsaDetail->kuota }}</p>
                                                    </li>
                                                    <li>
                                                        <div class="icon">
                                                            <span class="icon-clock"></span>
                                                        </div>
                                                        <p>{{ $kel->kelasAnsaDetail->waktu_mulai->diffInDays($kel->kelasAnsaDetail->waktu_selesai) }}
                                                            Hari</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xl-12">
                                        <div class="alert alert-warning" role="alert">
                                            <strong>Maaf!</strong> Tidak ada kelas yang tersedia.
                                        </div>
                                    </div>
                                @endforelse
                                <!--Courses Two Single End-->
                            </div>
                        </div>
                        {{ $kelas->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
