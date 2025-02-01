@extends('layouts.app')

@section('content')
    @PageHeader([
    'pageTitle' => $produkDigital->judul,
    'pageHeaderImg' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'bgImage' => $produkDigital->getFirstMediaUrl('produk-digital-thumbnail'),
    'breadcrumb' => [
    [
    'name' => $produkDigital->judul,
    'url' => route('produk-digital.show', $produkDigital->slug)
    ]
    ]
    ])
    <!--Start Product Details-->
    <section class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__img">
                        <img src="{{ $produkDigital->getFirstMediaUrl('produk-digital-thumbnail') }}"
                            alt="{{ $produkDigital->judul }}" style="mix-blend-mode: normal !important;" width="630px"
                            height="625px">
                    </div>
                </div>

                <div class="col-lg-6 col-xl-6">
                    <div class="product-details__top">
                        <h3 class="product-details__title">
                            {{ $produkDigital->judul }} <span>
                                Rp. {{ number_format($produkDigital->harga, 0, ',', '.') }}
                            </span>
                        </h3>
                    </div>
                    <div class="product-details__reveiw">
                        <span class="p-1 rounded bg-primary text-white">Platform: {{ $produkDigital->platform }}</span>
                    </div>
                    <div class="product-details__content">
                        <p class="product-details__content-text1">{!! $produkDigital->deskripsi !!}</p>
                        <p class="product-details__content-text2">Stok:
                            {{ $produkDigital->is_unlimited ? 'Tidak Terbatas' : $produkDigital->qty }} <br>
                            <span
                                class="text-{{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'success' : 'danger' }}">
                                {{ $produkDigital->is_unlimited || $produkDigital->qty > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </p>
                    </div>

                    <div class="product-details__buttons">
                        <div class="product-details__buttons-1">
                            <a class="thm-btn" href="cart.html">Beli Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Product Details-->
@endsection
