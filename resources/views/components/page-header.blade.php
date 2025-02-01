@props([
    'bgImage' => asset('assets/images/shapes/page-header-bg-shape.png'),
    'pageHeaderImg' => asset('assets/images/resources/page-header-img-1.png'),
    'pageTitle' => 'Blog List',
    'breadcrumb' => [['name' => 'Home', 'url' => 'index.html'], ['name' => 'Blog List']],
])
<!--Page Header Start-->
<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ $bgImage }});">
    </div>
    <div class="page-header__shape-4">
        <img src="{{ asset('assets/images/shapes/page-header-shape-4.png') }}" alt="">
    </div>
    <div class="page-header__shape-5">
        <img src="{{ asset('assets/images/shapes/page-header-shape-5.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <div class="page-header__img">
                <img src="{{ $pageHeaderImg }}" alt="" width="450px" height="378px">
                <div class="page-header__shape-1">
                    <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
                </div>
                <div class="page-header__shape-2">
                    <img src="{{ asset('assets/images/shapes/page-header-shape-2.png') }}" alt="">
                </div>
                <div class="page-header__shape-3">
                    <img src="{{ asset('assets/images/shapes/page-header-shape-3.png') }}" alt="">
                </div>
            </div>
            <h2>{{ $pageTitle }}</h2>
            <div class="thm-breadcrumb__box">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="{{ route('index') }}">Beranda</a></li>
                    <li><span>//</span></li>
                    @foreach ($breadcrumb as $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['name'] }}</a></li>
                        @if (!$loop->last)
                            <li><span>//</span></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<!--Page Header End-->
