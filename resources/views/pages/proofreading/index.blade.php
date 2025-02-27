@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Proofreading',
    'pageHeaderImg' => $webResource->getFirstMediaUrl('proofreading_banner'),
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
                                                <!-- Discount Badge (if applicable) -->
                                                @if (isset($promoKategori) || isset($promoProducts[$proofreading->id]))
                                                    @php
                                                        $discountPercentage = isset($promoProducts[$proofreading->id])
                                                            ? $promoProducts[$proofreading->id]
                                                            : (isset($promoKategori)
                                                                ? $promoKategori->persentase
                                                                : 0);
                                                    @endphp
                                                    <div
                                                        style="position: absolute; top: 55px; left: 10px; background-color: #FF9900; color: white; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; align-items: center;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" style="margin-right: 4px;">
                                                            <path
                                                                d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                                            </path>
                                                            <line x1="7" y1="7" x2="7.01"
                                                                y2="7"></line>
                                                        </svg>
                                                        Diskon {{ $discountPercentage }}%
                                                    </div>
                                                @endif
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
                                                        @if (isset($promoKategori) || isset($promoProducts[$proofreading->id]))
                                                            @php
                                                                $originalPrice = $proofreading->proofreadingPakets->min(
                                                                    'harga',
                                                                );
                                                                $discountPercentage = 0;

                                                                // Check if there's a product-specific promo
                                                                if (isset($promoProducts[$proofreading->id])) {
                                                                    $discountPercentage =
                                                                        $promoProducts[$proofreading->id];
                                                                }
                                                                // Otherwise, use category promo if available
                                                                elseif (isset($promoKategori)) {
                                                                    $discountPercentage = $promoKategori->persentase;
                                                                }

                                                                $discountedPrice =
                                                                    $originalPrice -
                                                                    ($originalPrice * $discountPercentage) / 100;
                                                            @endphp
                                                            <p>
                                                                <span
                                                                    style="text-decoration: line-through; color: #999; margin-right: 8px; font-size: 14px;">
                                                                    {{ 'Rp ' . number_format($originalPrice, 0, ',', '.') }}
                                                                </span>
                                                                <span
                                                                    style="color: #FF6B6B; font-weight: 700; font-size: 18px;">
                                                                    {{ 'Rp ' . number_format($discountedPrice, 0, ',', '.') }}
                                                                </span>
                                                            </p>
                                                        @else
                                                            <p style="font-size: 18px; font-weight: 600;">Mulai dari
                                                                {{ 'Rp ' . number_format($proofreading->proofreadingPakets->min('harga'), 0, ',', '.') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <h3 class="courses-two__title">
                                                    <a href="{{ route('proofreading.show', $proofreading->slug) }}">[Proofreading]
                                                        {{ $proofreading->judul }}</a>
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
