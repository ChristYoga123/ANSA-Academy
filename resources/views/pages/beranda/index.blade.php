@extends('layouts.app')

@section('content')
    <!-- Main Slider Start -->
    <section class="main-slider">
        <div class="main-slider__carousel owl-carousel owl-theme">
            @forelse ($webAds as $item)
                <div class="item">
                    <div class="main-slider__bg"
                        style="background-image: url({{ $item->getFirstMediaUrl('ad-background') }});">
                    </div>
                    <div class="container">
                        <div class="main-slider__content">
                            <div class="main-slider__sub-title-box">
                                <div class="main-slider__sub-title-shape"></div>
                                <h5 class="main-slider__sub-title">{{ $item->headline }}</h5>
                            </div>
                            <h2 class="main-slider__title">{{ $item->judul }}</h2>
                            <p class="main-slider__text">
                                {{ $item->deskripsi }}
                            </p>
                            <div class="main-slider__btn-box">
                                <a href="{{ $item->url }}" class="thm-btn"><span class="icon-angles-right"></span>Lebih
                                    Detail</a>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="item">
                    <div class="main-slider__bg" style="background-image: url(assets/images/backgrounds/slider-1-1.jpg);">
                    </div>
                    <div class="container">
                        <div class="main-slider__content">
                            <div class="main-slider__sub-title-box">
                                <div class="main-slider__sub-title-shape"></div>
                                <h5 class="main-slider__sub-title">Best Online Platform</h5>
                            </div>
                            <h2 class="main-slider__title">Kembangkan Karirmu <br> Bersama Fistudy</h2>
                            <p class="main-slider__text">Tingkatkan perjalanan pendidikanmu dengan platform kursus
                                modern kami. <br> Nikmati kenyamanan belajar online, yang memungkinkan kamu menguasai
                                keterampilan baru <br> dengan kecepatan sendiri dan dari mana saja.
                            </p>
                            <div class="main-slider__btn-box">
                                <a href="contact.html" class="thm-btn"><span class="icon-angles-right"></span>Mulai
                                    Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
    <!--Main Slider Start -->

    <!-- Vision Start -->
    <section class="vision-section" style="padding: 120px 0 0 0;">
        <div class="container">
            <div class="section-title-two text-center sec-title-animation animation-style2">
                <div class="section-title-two__tagline-box">
                    <div class="section-title-two__tagline-shape">
                        <img src="assets/images/shapes/section-title-two-shape-1.png" alt="">
                    </div>
                    <span class="section-title-two__tagline">Visi Kami</span>
                </div>
                <h2 class="section-title-two__title title-animation">Memberdayakan Melalui <span>Pendidikan</span></h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-8 text-center">
                    <div class="vision-section__content">
                        <p class="vision-section__text"
                            style="font-size: 18px; line-height: 1.8; margin-bottom: 30px; font-style: italic">
                            {!! $webResource->visi ?? 'Visi kami belum tersedia' !!}
                        </p>
                        {{-- quote by text center --}}
                        <div class="vision-section__quote-box">
                            <div class="vision-section__quote-shape" style="font: italic">
                                -CEO ANSA Academy-
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vision End -->

    <!-- Services Start -->
    <section class="services-section" style="padding: 120px 0 0 0;">
        <div class="container">
            <div class="section-title-two text-center sec-title-animation animation-style2">
                <div class="section-title-two__tagline-box">
                    <div class="section-title-two__tagline-shape">
                        <img src="assets/images/shapes/section-title-two-shape-1.png" alt="">
                    </div>
                    <span class="section-title-two__tagline">Layanan Kami</span>
                </div>
                <h2 class="section-title-two__title title-animation">Yang Kami <span>Tawarkan</span></h2>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay="100ms">
                    <div class="services-section__single text-center"
                        style="padding: 40px 30px; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px;">
                        <div class="services-section__icon" style="margin-bottom: 25px;">
                            <svg width="80" height="80" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"
                                fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path fill="none" stroke="#687EFF" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" d="M1 2h16v11H1z"></path>
                                    <path fill="none" stroke="#687EFF" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-miterlimit="10"
                                        d="M4 5.5v5s3-1 5 0v-5s-2-2-5 0zM9 5.5v5s3-1 5 0v-5s-2-2-5 0z"></path>
                                    <path fill="#687EFF" stroke="#687EFF" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-miterlimit="10" d="M8.5 14l-3 3h7l-3-3z"></path>
                                </g>
                            </svg>
                        </div>
                        <h3 class="services-section__title" style="font-size: 24px; margin-bottom: 15px;">Mentoring</h3>
                        <p class="services-section__text">Jenis program dalam ANSA Academy yang berfokus pada pengembangan
                            dan pendampingan mentee secara private baik secara indvidu maupun berkelompok.</p>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay="200ms">
                    <div class="services-section__single text-center"
                        style="padding: 40px 30px; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px;">
                        <div class="services-section__icon" style="margin-bottom: 25px;">
                            <svg height="80" width="80" version="1.1" id="_x32_"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 512 512" xml:space="preserve" fill="#687EFF">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <style type="text/css">
                                        .st0 {
                                            fill: #687EFF;
                                        }
                                    </style>
                                    <g>
                                        <path class="st0"
                                            d="M81.44,116.972c23.206,0,42.007-18.817,42.007-42.008c0-23.215-18.801-42.016-42.007-42.016 c-23.216,0-42.016,18.801-42.016,42.016C39.424,98.155,58.224,116.972,81.44,116.972z">
                                        </path>
                                        <path class="st0"
                                            d="M224.166,245.037c0-0.856-0.142-1.673-0.251-2.498l62.748-45.541c3.942-2.867,4.83-8.411,1.963-12.362 c-1.664-2.285-4.342-3.652-7.17-3.652c-1.877,0-3.667,0.589-5.191,1.689l-62.874,45.636c-2.341-1.068-4.909-1.704-7.65-1.704 h-34.178l-8.294-47.222c-4.555-23.811-14.112-42.51-34.468-42.51h-86.3C22.146,136.873,0,159.019,0,179.383v141.203 c0,10.178,8.246,18.432,18.424,18.432c5.011,0,0,0,12.864,0l7.005,120.424c0,10.83,8.788,19.61,19.618,19.61 c8.12,0,28.398,0,39.228,0c10.83,0,19.61-8.78,19.61-19.61l9.204-238.53h0.463l5.27,23.269c1.744,11.097,11.293,19.28,22.524,19.28 h51.534C215.92,263.461,224.166,255.215,224.166,245.037z M68.026,218.861v-67.123h24.126v67.123l-12.817,15.118L68.026,218.861z">
                                        </path>
                                        <polygon class="st0"
                                            points="190.326,47.47 190.326,200.869 214.452,200.869 214.452,71.595 487.874,71.595 487.874,302.131 214.452,302.131 214.452,273.113 190.326,273.113 190.326,326.256 512,326.256 512,47.47 ">
                                        </polygon>
                                        <path class="st0"
                                            d="M311.81,388.597c0-18.801-15.235-34.029-34.028-34.029c-18.801,0-34.036,15.228-34.036,34.029 c0,18.785,15.235,34.028,34.036,34.028C296.574,422.625,311.81,407.381,311.81,388.597z">
                                        </path>
                                        <path class="st0"
                                            d="M277.781,440.853c-24.259,0-44.866,15.919-52.782,38.199h105.565 C322.648,456.771,302.04,440.853,277.781,440.853z">
                                        </path>
                                        <path class="st0"
                                            d="M458.573,388.597c0-18.801-15.235-34.029-34.028-34.029c-18.801,0-34.036,15.228-34.036,34.029 c0,18.785,15.235,34.028,34.036,34.028C443.338,422.625,458.573,407.381,458.573,388.597z">
                                        </path>
                                        <path class="st0"
                                            d="M424.545,440.853c-24.259,0-44.866,15.919-52.783,38.199h105.565 C469.411,456.771,448.804,440.853,424.545,440.853z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h3 class="services-section__title" style="font-size: 24px; margin-bottom: 15px;">Kelas ANSA</h3>
                        <p class="services-section__text">Menawarkan pemberian materi bersama-sama dengan mentee lainnya
                            dan dengan mengembangkan minat sesuai dengan kebutuhan.</p>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="services-section__single text-center"
                        style="padding: 40px 30px; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px;">
                        <div class="services-section__icon" style="margin-bottom: 25px;">
                            <svg width="80" height="80" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg"
                                xml:space="preserve" fill="none">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g style="display:inline;stroke-width:9.40549;stroke-dasharray:none">
                                        <path
                                            d="M38 137h48c2.828 0 7.173 2.935 10 3 2.7.062 7.3-3 10-3h48V49h-48c-3 0-7 3-9.704 3C93 52 89 49 86 49H38Zm58-82v85"
                                            style="fill:none;stroke:#687EFF;stroke-width:9.40549;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:5;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(-26.483 -24.57) scale(1.27586)"></path>
                                    </g>
                                    <g style="stroke-width:6.27027;stroke-dasharray:none">
                                        <path
                                            d="M51.869 65.116h30.297M51.869 80.088h30.297M51.869 95.06h30.297m27.668-29.944h30.297m-30.297 14.972h30.297"
                                            style="fill:none;fill-opacity:1;stroke:#687EFF;stroke-width:9.40541;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:5;stroke-dasharray:none;stroke-opacity:1"
                                            transform="translate(-26.483 -24.57) scale(1.27586)"></path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h3 class="services-section__title" style="font-size: 24px; margin-bottom: 15px;">Proofreading
                        </h3>
                        <p class="services-section__text">Mereview hasil naskah lomba atua riset dengan mencari dan
                            mengevaluasi naskah sehingga menghasilkan naskah terbaik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services End -->

    <!-- About One Start -->
    <section class="about-one">
        <div class="about-one__shape-1">
            <img src="{{ asset('assets/images/shapes/about-one-shape-1.png') }}" alt="">
        </div>
        <div class="about-one__shape-2">
            <img src="{{ asset('assets/images/shapes/about-one-shape-2.png') }}" alt="">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6 wow slideInLeft" data-wow-delay="100ms" data-wow-duration="2500ms">
                    <div class="about-one__left">
                        <div class="about-one__left-shape-1 rotate-me"></div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="about-one__img-box">
                                    <div class="about-one__img">
                                        <img src="assets/images/resources/abou-one-img-1.jpg" alt="">
                                    </div>
                                </div>
                                <div class="about-one__awards-box">
                                    <div class="about-one__awards-count-box">
                                        <h3 class="odometer" data-count="45">00</h3>
                                        <span>+</span>
                                    </div>
                                    <p>Awards Winning</p>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="about-one__experience-box">
                                    <div class="about-one__experience-box-inner">
                                        <div class="about-one__experience-icon">
                                            <img src="assets/images/icon/about-one-experience-icon.png" alt="">
                                        </div>
                                        <div class="about-one__experience-count-box">
                                            <div class="about-one__experience-count">
                                                <h3 class="odometer" data-count="25">00</h3>
                                                <span>+</span>
                                                <p>Years</p>
                                            </div>
                                            <p>of experience</p>
                                        </div>
                                    </div>
                                    <div class="about-one__experience-box-shape"></div>
                                </div>
                                <div class="about-one__img-box-2">
                                    <div class="about-one__img-2">
                                        <img src="assets/images/resources/abou-one-img-2.jpg" alt="">
                                    </div>
                                    <div class="about-one__img-shape-1 float-bob-y">
                                        <img src="assets/images/shapes/about-one-img-shape-1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-one__right">
                        <div class="section-title text-left sec-title-animation animation-style2">
                            <div class="section-title__tagline-box">
                                <div class="section-title__tagline-shape"></div>
                                <span class="section-title__tagline">Tentang Kami</span>
                            </div>
                            <h2 class="section-title__title title-animation">Cerita Kami: Dibangun dengan Nilai,
                                Digerakkan oleh <span>Inovasi <img src="assets/images/shapes/section-title-shape-1.png"
                                        alt=""></span>
                            </h2>
                        </div>
                        <p class="about-one__text">{!! $webResource->tentang ?? 'Tentang kami belum tersedia' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About One End -->

    <!--Courses Two Start -->
    <section class="courses-two">
        <div class="container">
            <div class="section-title-two text-left sec-title-animation animation-style2">
                <div class="section-title-two__tagline-box">
                    <div class="section-title-two__tagline-shape">
                        <img src="assets/images/shapes/section-title-two-shape-1.png" alt="">
                    </div>
                    <span class="section-title-two__tagline">Program Kami</span>
                </div>
                <h2 class="section-title-two__title title-animation">Jelajahi Program<br> yang Kami
                    <span>Tawarkan</span>
                </h2>
            </div>
            <div class="courses-two__inner">
                <ul class="courses-two__filter style1 post-filter list-unstyled clearfix">
                    <li data-filter=".filter-item" class="active">
                        <p><span class="icon-catagory"></span>Semua</p>
                    </li>
                    <li data-filter=".marketing">
                        <p><span class="icon-bullhorn"></span>Mentoring</p>
                    </li>
                    <li data-filter=".design">
                        <p><span class="icon-pen-ruler"></span>Kelas ANSA</p>
                    </li>
                    <li data-filter=".programming">
                        <p><span class="icon-computer"></span>Proofreading</p>
                    </li>
                </ul>

                <div class="row filter-layout">
                    <!-- Mentoring Programs (marketing class) -->
                    @foreach ($mentoringPrograms as $program)
                        <div class="col-xl-4 col-lg-6 col-md-6 filter-item marketing">
                            <div class="courses-two__single">
                                <div class="courses-two__img-box">
                                    <div
                                        style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                        {{ $program->program }}
                                    </div>
                                    <div class="courses-two__img">
                                        <img src="{{ $program->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                            alt="{{ $program->judul }}" width="368px" height="230px">
                                    </div>
                                    <div class="courses-two__heart">
                                        <a href="#"><span class="icon-heart"></span></a>
                                    </div>
                                </div>
                                <div class="courses-two__content">
                                    <div class="courses-two__doller-and-review">
                                        <div class="courses-two__doller">
                                            <p>Mulai dari
                                                {{ 'Rp ' . number_format($program->mentoringPakets->min('harga'), 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <h3 class="courses-two__title">
                                        <a href="#">{{ $program->judul }}</a>
                                    </h3>
                                    <div class="courses-two__btn-and-client-box">
                                        <div class="courses-two__btn-box">
                                            <a href="#" class="thm-btn-two">
                                                <span>Daftar Sekarang</span>
                                                <i class="icon-angles-right"></i>
                                            </a>
                                        </div>
                                        <div class="courses-two__client-box">
                                            <div class="courses-two__client-content">
                                                <h4>Jumlah Mentor</h4>
                                                <p>{{ $program->mentors_count }} Mentor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="courses-two__meta list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-book"></span>
                                            </div>
                                            <p>{{ $program->mentoring_pakets_count }} Paket
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Kelas ANSA Programs (design class) -->
                    @foreach ($kelasAnsaPrograms as $program)
                        <div class="col-xl-4 col-lg-6 col-md-6 filter-item design">
                            <div class="courses-two__single">
                                <div class="courses-two__img-box">
                                    <div
                                        style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                        {{ $program->program }}
                                    </div>
                                    <div class="courses-two__img">
                                        <img src="{{ $program->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                            alt="{{ $program->judul }}" width="368px" height="230px">
                                    </div>
                                    <div class="courses-two__heart">
                                        <a href="#"><span class="icon-heart"></span></a>
                                    </div>
                                </div>
                                <div class="courses-two__content">
                                    <div class="courses-two__doller-and-review">
                                        <div class="courses-two__doller">
                                            <p>Mulai dari
                                                {{ 'Rp ' . number_format($program->kelasAnsaPakets->min('harga'), 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <h3 class="courses-two__title">
                                        <a href="#">{{ $program->judul }}</a>
                                    </h3>
                                    <div class="courses-two__btn-and-client-box">
                                        <div class="courses-two__btn-box">
                                            <a href="#" class="thm-btn-two">
                                                <span>Daftar Sekarang</span>
                                                <i class="icon-angles-right"></i>
                                            </a>
                                        </div>
                                        <div class="courses-two__client-box">
                                            <div class="courses-two__client-content">
                                                <h4>Jumlah Mentor</h4>
                                                <p>{{ $program->mentors_count }} Mentor</p>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="courses-two__meta list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-calendar"></span>
                                            </div>
                                            <p>Mulai: {{ $program->kelasAnsaDetail->waktu_mulai->format('d M Y') }}</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-user-plus"></span>
                                            </div>
                                            <p>Kuota: {{ $program->kelasAnsaDetail->kuota }}</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-clock"></span>
                                            </div>
                                            <p>{{ $program->kelasAnsaDetail->waktu_mulai->diffInDays($program->kelasAnsaDetail->waktu_selesai) }}
                                                Hari</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Proofreading Programs (programming class) -->
                    @foreach ($proofreadingPrograms as $program)
                        <div class="col-xl-4 col-lg-6 col-md-6 filter-item programming">
                            <div class="courses-two__single">
                                <div class="courses-two__img-box">
                                    <div
                                        style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1;">
                                        {{ $program->program }}
                                    </div>
                                    <div class="courses-two__img">
                                        <img src="{{ $program->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                            alt="{{ $program->judul }}" width="368px" height="230px">
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
                                                {{ 'Rp ' . number_format($program->proofreadingPakets->min('harga'), 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <h3 class="courses-two__title">
                                        <a href="#">{{ $program->judul }}</a>
                                    </h3>
                                    <div class="courses-two__btn-and-client-box">
                                        <div class="courses-two__btn-box">
                                            <a href="#" class="thm-btn-two">
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
                                            <p>{{ $program->proofreading_pakets_count }} Paket</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--Courses Two End -->

    <section class="video-one" style="margin-top: 150px">
        <div class="container">
            <div class="video-one__inner">
                <div class="video-one__shape-1"></div>
                <div class="video-one__shape-2 rotate-me"></div>
                <div style="position: relative; width: 100%; max-width: 800px; margin: 0 auto;">
                    <img src="assets/images/resources/video-one-img-1.jpg" alt=""
                        style="width: 100%; height: auto; display: block;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                        <a href="https://www.youtube.com/watch?v=rMfWdJ2qvtE" class="video-popup">
                            <div style="position: relative; width: 100%; height: 100%; overflow: hidden;">
                                <img src="https://img.youtube.com/vi/rMfWdJ2qvtE/maxresdefault.jpg" alt=""
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 2;"
                                class="video-one__video-icon">
                                <span class="fa fa-play" style="font-size: 24px;"></span>
                                <i class="ripple"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- buatkan quote author --}}
            <div class="row justify-content-center" style="margin-top: 50px">
                <div class="col-xl-8 text-center">
                    <div class="vision-section__content">
                        <p class="vision-section__text"
                            style="font-size: 18px; line-height: 1.8; margin-bottom: 30px; font-style: italic">
                            {!! $webResource->quote ?? 'Quote belum tersedia' !!}
                        </p>
                        {{-- quote by text center --}}
                        <div class="vision-section__quote-box">
                            <div class="vision-section__quote-shape" style="font: italic">
                                -CEO ANSA Academy-
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Testimonial Three Start -->
    <section class="testimonial-three" style="margin-bottom: 150px">
        <div class="testimonial-three__wrap">
            <div class="section-title-two text-center sec-title-animation animation-style1">
                <div class="section-title-two__tagline-box">
                    <div class="section-title-two__tagline-shape">
                        <img src="assets/images/shapes/section-title-two-shape-1.png" alt="">
                    </div>
                    <span class="section-title-two__tagline">Testimoni</span>
                </div>
                <h2 class="section-title-two__title title-animation">Apa Kata
                    <span>Mentee Kami</span>
                </h2>
            </div>
            <ul class="list-unstyled testimonial-three__list marquee_mode-3">
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-1.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Mitchel
                                        Watson</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-2.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Jessica
                                        Brown</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-3.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Asle
                                        Rose</a>
                                </h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-4.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Ruksana
                                        Rumi</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list-unstyled testimonial-three__list testimonial-three__list--two marquee_mode-4">
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-1.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Mitchel
                                        Watson</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-2.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Jessica
                                        Brown</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-3.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Asle
                                        Rose</a>
                                </h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="testimonial-three__single">
                        <div class="testimonial-three__rating">
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                            <span class="icon-star"></span>
                        </div>
                        <p class="testimonial-three__text">It has survived not only five centuries,
                            but also <br> the leap into electronic typesetting, remaining <br> essentially
                            unchanged. It was popularised in <br> the 1960s with the release.</p>
                        <div class="testimonial-three__client-info">
                            <div class="testimonial-three__client-img">
                                <img src="assets/images/testimonial/testimonial-3-4.jpg" alt="">
                            </div>
                            <div class="testimonial-three__client-content">
                                <h4 class="testimonial-three__client-name"><a href="testimonials.html">Ruksana
                                        Rumi</a></h4>
                                <p class="testimonial-three__client-sub-title">UI/UX Design</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!--Testimonial Three End -->

    <section class="faq-page" style="margin-bottom: 150px">
        <div class="section-title-two text-center sec-title-animation animation-style1">
            <div class="section-title-two__tagline-box">
                <div class="section-title-two__tagline-shape">
                    <img src="assets/images/shapes/section-title-two-shape-1.png" alt="">
                </div>
                <span class="section-title-two__tagline">Ada Pertanyaan?</span>
            </div>
            <h2 class="section-title-two__title title-animation">
                Pertanyaan yang Sering <span>Diajukan</span>
            </h2>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-6">
                    <div class="faq-page__left">
                        <div class="accrodion-grp faq-one-accrodion" data-grp-name="faq-one-accrodion-1">
                            @forelse ($webResource->faqs as $uuid => $value)
                                <div class="accrodion {{ $loop->first ? 'active' : '' }}">
                                    <div class="accrodion-title">
                                        <h4>{{ $value['pertanyaan'] }}</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>{{ $value['jawaban'] }}</p>
                                        </div><!-- /.inner -->
                                    </div>
                                </div>
                            @empty
                                <p>Belum ada pertanyaan yang sering diajukan</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-6 col-lg-6">
                    <div class="faq-page__right">
                        <div class="accrodion-grp faq-one-accrodion" data-grp-name="faq-one-accrodion-1">
                            <div class="accrodion">
                                <div class="accrodion-title">
                                    <h4>What services do you offer for development ?</h4>
                                </div>
                                <div class="accrodion-content">
                                    <div class="inner">
                                        <p>We provide a range of services designed to help your business grow and
                                            succeed. Our services include market research and analysis, strategic
                                            planning, sales and marketing strategy development</p>
                                    </div><!-- /.inner -->
                                </div>
                            </div>
                            <div class="accrodion">
                                <div class="accrodion-title">
                                    <h4>How can your consultancy help my business grow?</h4>
                                </div>
                                <div class="accrodion-content">
                                    <div class="inner">
                                        <p>We provide a range of services designed to help your business grow and
                                            succeed. Our services include market research and analysis, strategic
                                            planning, sales and marketing strategy development</p>
                                    </div><!-- /.inner -->
                                </div>
                            </div>
                            <div class="accrodion">
                                <div class="accrodion-title">
                                    <h4>What types of businesses do you work with?</h4>
                                </div>
                                <div class="accrodion-content">
                                    <div class="inner">
                                        <p>We provide a range of services designed to help your business grow and
                                            succeed. Our services include market research and analysis, strategic
                                            planning, sales and marketing strategy development</p>
                                    </div><!-- /.inner -->
                                </div>
                            </div>
                            <div class="accrodion">
                                <div class="accrodion-title">
                                    <h4>How do you tailor your services to my business’s needs?</h4>
                                </div>
                                <div class="accrodion-content">
                                    <div class="inner">
                                        <p>We provide a range of services designed to help your business grow and
                                            succeed. Our services include market research and analysis, strategic
                                            planning, sales and marketing strategy development</p>
                                    </div><!-- /.inner -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
