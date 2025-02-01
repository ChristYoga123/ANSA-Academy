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

    <section class="product">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-12">
                    <div class="product__sidebar">
                        <div class="shop-search product__sidebar-single">
                            <form action="{{ route('produk-digital.search') }}" method="POST">
                                @csrf
                                <input type="text" placeholder="Search" name="search">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-12">
                    <div class="product__items">

                        <div class="product__all">
                            <div class="row">
                                @forelse ($produkDigitals as $produkDigital)
                                    <!--Product All Single Start-->
                                    <div class="col-xl-4 col-lg-4 col-md-6">
                                        <div class="product__all-single">
                                            <div class="product__all-img">

                                                <img src="{{ $produkDigital->getFirstMediaUrl('produk-digital-thumbnail') }}"
                                                    alt="">
                                                <img src="{{ $produkDigital->getFirstMediaUrl('produk-digital-thumbnail') }}"
                                                    alt="">
                                            </div>
                                            <div class="product__all-content">
                                                <!-- Ganti review dengan informasi platform -->
                                                <div class="product__all-info">
                                                    <span class="badge bg-primary text-light ">
                                                        <i
                                                            class="icon-{{ $produkDigital->platform == 'file' ? 'download' : 'link' }}"></i>
                                                        {{ ucfirst($produkDigital->platform) }}
                                                    </span>
                                                </div>

                                                <h4 class="product__all-title">
                                                    <a
                                                        href="{{ route('produk-digital.show', $produkDigital->slug) }}">{{ $produkDigital->judul }}</a>
                                                </h4>

                                                <p class="product__all-price">
                                                    Rp. {{ number_format($produkDigital->harga, 0, ',', '.') }}
                                                </p>

                                                <div class="product__all-btn-box">
                                                    <a class="thm-btn product__all-btn"
                                                        href="{{ route('produk-digital.show', $produkDigital->slug) }}">Lihat
                                                        Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Product All Single End-->
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center" role="alert">
                                            <strong>Maaf!</strong> Belum ada produk digital yang tersedia.
                                        </div>
                                    </div>
                                @endforelse
                                {{ $produkDigitals->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
