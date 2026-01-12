@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Cart')

@section('content')

@php
// 1. Total harga produk
$totalPrice = $cartItems->sum(fn($item) => $item->ticket->price);

// 2. Diskon dari voucher 
$discount = session('voucher_discount', 0);

// 3. Admin fee anti diskon
$adminFee = ceil($totalPrice * 0.05);
$subtotalAfterDiscount = max($totalPrice - $discount, 0);
$totalFull = $subtotalAfterDiscount + $adminFee;
@endphp


                    <div class="container" style="padding-bottom: 150px;">
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
                                <img src="/storage/event_images/{{ $item->ticket->event->image }}.webp" alt="" loading="lazy" />
                            </div>
                            <div class="cart-content">
                                <h6 class="title mb-1">
                                    <a href="{{ route('event_tickets', $item->ticket->event->id) }}">{{ $item->ticket->event->name }}</a>
                                </h6>
                                <span class="font-12 brand-tag">{{ $item->ticket->name }}</span>
                                <span class="font-12 brand-tag">
                                    @if(!empty($item->notes))
                                        <small class="text-muted">- {{ $item->notes }}</small>
                                    @endif
                                </span>
                                <div class="cart-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                    <h6 class="price mb-0">Rp. {{ number_format($item->ticket->price, 0, ',', '.') }}</h6>
                                    <form action="{{ route('cart.remove') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center mt-5">
                            <img src="https://placehold.co/300x300/f8f9fa/6c757d?text=Cart+Is+Empty" alt="Cart is empty" class="img-fluid mb-3" style="max-width: 200px; border-radius: 15px;">
                            <h4>Your Cart is Empty</h4>
                            <p class="text-muted">It looks like you haven't added any tickets yet.</p>
                        </div>
                        @endforelse
                    </div>
                    @if(!$cartItems->isEmpty())
                    <div class="fixed-bottom bg-light p-3 shadow-lg" style="border-top: 1px solid #dee2e6;">
                        <div class="container d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-0">Rp. {{ number_format($totalFull, 0, ',', '.') }}</h4>
                                <div class="lh-1">
                                    <small class="text-muted d-block">
                                        Total Product: Rp. {{ number_format($totalPrice, 0, ',', '.') }}
                                    </small>
                                    @if($discount > 0)
                    <small class="text-success d-block">
                        Discount: - Rp. {{ number_format($discount, 0, ',', '.') }}
                    </small>
                    @endif
                <small class="text-muted d-block">
                    Admin Fee: Rp. {{ number_format($adminFee, 0, ',', '.') }}
                </small>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#checkoutConfirmModal">
            Checkout
        </button>
    </div>
</div>

<div class="modal fade" id="checkoutConfirmModal" tabindex="-1"
     aria-labelledby="checkoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="checkoutConfirmModalLabel">
                    Confirm Checkout
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <form action="{{ route('voucher.apply') }}"
                      method="POST"
                      class="input-group mb-3">
                    @csrf
                    <input type="text"
                           name="voucher_code"
                           class="form-control"
                           placeholder="Promotion Code">
                    <button type="submit"
                            class="btn btn-outline-primary">
                        Use Code
                    </button>
                </form>
                <hr>
                <p class="fs-5">
                    You are about to proceed to checkout with the following details:
                </p>
                <h2 class="fw-bold text-success mb-2">
                    Rp. {{ number_format($totalFull, 0, ',', '.') }}
                </h2>
                <p class="text-muted mb-1">
                    Total Product: Rp. {{ number_format($totalPrice, 0, ',', '.') }}
                </p>
                @if($discount > 0)
                    <p class="text-success mb-1">
                        Discount: - Rp. {{ number_format($discount, 0, ',', '.') }}
                    </p>
                @endif
                <p class="text-muted mb-3">
                    Admin Fee: Rp. {{ number_format($adminFee, 0, ',', '.') }}
                </p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="button"
                            class="btn btn-secondary btn-lg"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-success btn-lg">
                        Confirm & Continue
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


@endif

@endsection