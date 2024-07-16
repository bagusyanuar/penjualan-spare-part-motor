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
            // eventProductAction();
        })
    </script>
@endsection
