@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Dashboard')

@section('content')
<div class="dz-banner">
    <div class="swiper banner-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="banner-bg" style="background-image: url('/pic1.webp');"></div>
            </div>
        </div>
        <div class="swiper-pagination style-2"></div>
    </div>
</div>
<!-- Banner End -->


<div class="title-bar container mb-0 pb-0">
    <h5 class="title font-w800 mb-0">Upcoming</h5>
</div>

<!-- Shop Section Strat -->
<div class="container">
    <div class="row g-3">
        <div class="col-6">
            @forelse ($upcomingEvents as $event)
            <a href="{{ route('event_tickets', $event->id) }}">
                <div class="shop-card">
                    <div class="dz-media">
                        <img src="/storage/event_images/{{ $event->image }}.webp" alt="image" />
                    </div>
                    <div class="dz-content">
                        <span class="font-12 text-muted">{{ $event->location }}</span>
                        <h6 class="title">
                            <a href="{{ route('event_tickets', $event->id) }}">{{ $event->name }}</a>
                        </h6>
                    </div>
                </div>
            </a>
            @empty
            <p>No upcoming events available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection