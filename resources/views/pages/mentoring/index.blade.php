@extends('layouts.app')

@push('styles')
    <style>
        .course-grid__radio-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 0;
            width: 100%;
        }

        .course-grid__radio {
            margin-right: 10px;
        }

        .course-grid__radio-text {
            font-size: 15px;
            color: #878e9c;
        }

        .course-grid__radio:checked+.course-grid__radio-text {
            color: #0d6efd;
            font-weight: 500;
        }
    </style>
@endpush
@section('content')
    @PageHeader([
    'pageTitle' => 'Mentoring',
    'pageHeaderImg' => $webResource->getFirstMediaUrl('mentoring_banner'),
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
                            <div class="course-grid__skill course-grid__single">
                                <div class="course-grid__title-box">
                                    <h3 class="course-grid__title">Kategori</h3>
                                    <div class="course-grid__title-shape-1">
                                        <img src="{{ asset('assets/images/shapes/course-grid-title-shape-1.png') }}"
                                            alt="">
                                    </div>
                                </div>
                                <!-- Update the category list to use radio buttons -->
                                <ul class="list-unstyled course-grid__list-item">
                                    <li>
                                        <label class="course-grid__radio-label">
                                            <input type="radio" name="category" value="" checked
                                                class="course-grid__radio">
                                            <span class="course-grid__radio-text">Semua Kategori</span>
                                        </label>
                                    </li>
                                    @foreach ($kategories as $kategori)
                                        <li>
                                            <label class="course-grid__radio-label">
                                                <input type="radio" name="category" value="{{ $kategori->id }}"
                                                    class="course-grid__radio">
                                                <span class="course-grid__radio-text">{{ $kategori->nama }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="course-grid__right">
                        <div class="course-grid__right-content-box">
                            <div class="row">
                                @forelse ($mentorings as $mentoring)
                                    <div class="col-xl-6">
                                        <div class="courses-two__single">
                                            <div class="courses-two__img-box">
                                                <!-- Program Label -->
                                                <div
                                                    style="position: absolute; top: 10px; left: 10px; background-color: #FF6B6B; color: white; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; z-index: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                    {{ $mentoring->program }}
                                                </div>

                                                <!-- Discount Badge (if applicable) -->
                                                @if (isset($promoKategori) || isset($promoProducts[$mentoring->id]))
                                                    @php
                                                        $discountPercentage = isset($promoProducts[$mentoring->id])
                                                            ? $promoProducts[$mentoring->id]
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

                                                <!-- Course Image -->
                                                <div class="courses-two__img">
                                                    <img src="{{ $mentoring->getFirstMediaUrl('program-thumbnail') ?? 'assets/images/default.jpg' }}"
                                                        alt="{{ $mentoring->judul }}" width="368px" height="230px"
                                                        style="object-fit: cover; border-radius: 8px 8px 0 0;">
                                                </div>

                                                <!-- Heart Icon -->
                                                <div class="courses-two__heart">
                                                    <a href="{{ route('mentoring.show', $mentoring->slug) }}"><span
                                                            class="icon-heart"></span></a>
                                                </div>
                                            </div>

                                            <div class="courses-two__content"
                                                style="border-radius: 0 0 8px 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                                <div class="courses-two__doller-and-review">
                                                    <!-- Price Display -->
                                                    <div class="courses-two__doller">
                                                        @if (isset($promoKategori) || isset($promoProducts[$mentoring->id]))
                                                            @php
                                                                $originalPrice = $mentoring->mentoringPakets->min(
                                                                    'harga',
                                                                );
                                                                $discountPercentage = 0;

                                                                // Check if there's a product-specific promo
                                                                if (isset($promoProducts[$mentoring->id])) {
                                                                    $discountPercentage =
                                                                        $promoProducts[$mentoring->id];
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
                                                                {{ 'Rp ' . number_format($mentoring->mentoringPakets->min('harga'), 0, ',', '.') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Title -->
                                                <h3 class="courses-two__title" style="margin-top: 10px; font-weight: 600;">
                                                    <a href="{{ route('mentoring.show', $mentoring->slug) }}"
                                                        style="color: #333; transition: color 0.3s;">[Mentoring]
                                                        {{ $mentoring->judul }}</a>
                                                </h3>

                                                <!-- Button and Mentor Info -->
                                                <div class="courses-two__btn-and-client-box" style="margin-top: 15px;">
                                                    <div class="courses-two__btn-box">
                                                        <a href="{{ route('mentoring.show', $mentoring->slug) }}"
                                                            class="thm-btn-two"
                                                            style="display: inline-flex; align-items: center; transition: transform 0.3s;">
                                                            <span>Daftar Sekarang</span>
                                                            <i class="icon-angles-right" style="margin-left: 8px;"></i>
                                                        </a>
                                                    </div>
                                                    <div class="courses-two__client-box">
                                                        <div class="courses-two__client-content">
                                                            <h4 style="font-size: 13px; color: #777;">Jumlah Mentor</h4>
                                                            <p style="font-size: 15px; font-weight: 600;">
                                                                {{ $mentoring->mentors_count }} Mentor</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Meta Info -->
                                                <ul class="courses-two__meta list-unstyled"
                                                    style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0;">
                                                    <li>
                                                        <div class="icon"
                                                            style="background-color: rgba(255, 107, 107, 0.1); color: #FF6B6B; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%;">
                                                            <span class="icon-book"></span>
                                                        </div>
                                                        <p style="font-weight: 500;">
                                                            {{ $mentoring->mentoring_pakets_count }} Paket
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xl-12">
                                        <div class="alert alert-warning" role="alert"
                                            style="border-radius: 8px; border-left: 4px solid #ffc107; padding: 16px 20px;">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('.course-grid__radio');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const categoryId = this.value;

                    // Make AJAX request
                    fetch(`{{ route('mentoring.search.category') }}?category=${categoryId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            // Update only the mentoring grid section
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector(
                                '.course-grid__right-content-box');
                            document.querySelector('.course-grid__right-content-box')
                                .innerHTML = newContent.innerHTML;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
@endpush
