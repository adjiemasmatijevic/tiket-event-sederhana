@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Dashboard')

@section('content')
<div class="dz-banner">
    <div class="swiper banner-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="banner-bg" style="
                      background-image: url('/user/images/banner/pic1.jpg');
                    "></div>
                <div class="banner-content"><br><br><br><br><br>
                    <a href="product.html" class="btn btn-primary btn-sm">Get Ticket</a>
                </div>
            </div>
        </div>
        <div class="swiper-pagination style-2"></div>
    </div>
</div>
<!-- Banner End -->


<div class="title-bar container mb-0 pb-0">
    <h5 class="title font-w800 mb-0">Upcoming</h5>
    <a href="product.html">See more</a>
</div>

<!-- Shop Section Strat -->
<div class="container">
    <div class="row g-3">

        <div class="col-6">
            <div class="shop-card">
                <div class="dz-media">
                    <a href="product-detail.html">
                        <img src="/user/images/popular/pic2.jpg" alt="image" />
                    </a>
                    <a href="javascript:void(0);" class="item-bookmark active">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                    </a>
                </div>
                <div class="dz-content">
                    <span class="font-12">Ruang-Raung Spasi II</span>
                    <h6 class="title">
                        <a href="product-detail.html">an intimate; Raya Daendels Tour 2025</a>
                    </h6>
                    <h6 class="price">Rp50.000<del>Rp65.000</del></h6>
                </div>
                <div class="product-tag">
                    <span class="badge badge-secondary">23% off</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection