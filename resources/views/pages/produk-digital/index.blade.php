@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => 'Produk Digital',
    'breadcrumb' => [
    [
    'name' => 'Produk Digital',
    'url' => route('produk-digital.index')
    ]
    ]
    ])

    <section class="blog-list">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-list__left">
                        @forelse ($produkDigitals as $produkDigital)
                        @empty
                            <div class="blog-list__single">
                                <div class="blog-list__content">
                                    <h3 class="blog-list__title-1">Belum ada Produk Digital</h3>
                                </div>
                            </div>
                        @endforelse
                        {{ $produkDigitals->links('vendor.pagination.custom') }}
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
                            <form action="{{ route('produk-digital.search') }}" class="sidebar__search-form" method="POST">
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
