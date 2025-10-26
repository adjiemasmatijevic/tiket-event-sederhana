@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Cart')

@section('content')
<div class="container">
    <br>
    @if (session('success'))
    <div class="mb-3 fs-3 alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-3 fs-3 alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @forelse ($cartItems as $item)
    <div class="cart-box">
        <div class="dz-media">
            <img src="assets/images/popular/pic1.jpg" alt="" />
        </div>
        <div class="cart-content">
            <h6 class="title mb-1">
                <a href="product-detail.html">{{ $item->ticket->name }}</a>
            </h6>
            <span class="font-12 brand-tag">Peter Longline Pure Cotten Tshirt</span>
            <div class="cart-footer">
                <h6 class="price mb-0">$158.15</h6>
            </div>
        </div>
    </div>
    @empty
    <p>Cart is empty</p>
    @endforelse
</div>
@endsection