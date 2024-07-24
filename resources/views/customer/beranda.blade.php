@extends('customer.layout')

@section('content')
    <div class="slick-banner mb-5">
        <div class="banner-container">
            <img src="{{ asset('/assets/images/banner-1.png') }}" alt="img-banner">
        </div>
        <div class="banner-container">
            <img src="{{ asset('/assets/images/banner-1.png') }}" alt="img-banner">
        </div>
    </div>
    <section id="new-product-section" class="content-section mb-3">
        <p class="section-title">PRODUK TERBARU</p>
        <div class="product-container mb-3">
            @foreach($products as $product)
                <div class="card-product" data-id="{{ $product->id }}">
                    <div class="image-container">
                        <img src="{{ $product->gambar }}" alt="img-product">
                    </div>
                    <div class="product-info w-100">
                        <p class="product-name">{{ $product->nama }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="product-price">Rp.{{ number_format($product->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="product-action">
                        <a href="#" class="btn-shop" data-id="{{ $product->id }}">
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-100 d-flex justify-content-center">
            <a href="{{ route('customer.product') }}" style="font-size: 1em; color: var(--bg-primary); text-decoration: none;">Lihat Produk Lainnya</a>
        </div>
    </section>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/slick/slick-theme.css') }}"/>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>

        function setupSlickBanner() {
            $('.slick-banner').slick({
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                autoplay: true,
                autoplaySpeed: 1000,
            })
        }

        function setupSlickBrand() {
            $('.slick-brand').slick({
                infinite: true,
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                arrows: false,
                autoplaySpeed: 1000,
            })
        }

        function eventProductAction() {
            $('.card-product').on('click', function () {
                let id = this.dataset.id;
                window.location.href = '/product/' + id;
            })

            $('.btn-shop').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let id = this.dataset.id;
                window.location.href = '/product/' + id;
            })
        }

        $(document).ready(function () {
            setupSlickBanner();
            // setupSlickBrand();
            eventProductAction();
        })
    </script>
@endsection
