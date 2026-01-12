@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', $event->name)

@section('content')
<div class="container p-0">
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

    <div class="dz-product-preview">
        <div class="swiper product-detail-swiper-2">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="dz-media">
                        <img src="/storage/event_images/{{ $event->image }}.webp" alt="" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper product-detail-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="dz-media">
                        <img src="/storage/event_images/{{ $event->image }}.webp" alt="" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="dz-product-detail">
            <div class="detail-content">
                <span class="brand-tag">{{ $event->location }}</span>
                <h5>{{ $event->name }}</h5>
            </div>
            @if ($event->tickets->count() > 1)
            <div class="dz-review-meta mb-3">
                <h4 class="price">
                    Rp. {{ number_format($event->tickets()->min('price'), 0, ',', '.') }} - Rp. {{ number_format($event->tickets()->max('price'), 0, ',', '.') }}
                </h4>
            </div>
            @else
            <div class="dz-review-meta mb-3">
                <h4 class="price">
                    Rp. {{ number_format($event->tickets()->first()->price, 0, ',', '.') }}
                </h4>
            </div>
            @endif
            <h6>Description:</h6>
            <p>{!! $event->description !!}</p>
            {{-- <hr><br>
            <h6>Term & Condition:</h6>
            1. Term n Condition #1 <br>
            2. Term n Condition #2 --}}
            <div style="height: 100px;"></div>

        </div>
    </div>
</div>

<div class="fixed-bottom bg-white p-3 shadow-lg">
    <div class="container"> <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#addToCartModal">
            Buy Ticket
        </button>
    </div>
</div>


<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Order: {{ $event->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

           <form action="{{ route('event_tickets.add_to_cart') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="hidden" name="notes" id="notes">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ticket_id" class="form-label">Ticket</label>
                    <select class="form-select" id="ticket_id" name="ticket_id" required>
                        <option value="" disabled selected>-- Select Ticket --</option>
                        @foreach($event->tickets as $ticket)
                            <option value="{{ $ticket->id }}"
                                    data-option="{{ $ticket->option_value }}">
                                {{ $ticket->name }} (Rp. {{ number_format($ticket->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3" id="optionWrapper" style="display:none;">
                    <label class="form-label">Pilih Paket</label>
                    <select class="form-select" id="ticket_option_select"></select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </div>
        </form>
                </div>
            </div>
        </div>
<script>
const ticketSelect = document.getElementById('ticket_id');
const optionWrapper = document.getElementById('optionWrapper');
const optionSelect = document.getElementById('ticket_option_select');
const notesInput = document.getElementById('notes');

ticketSelect.addEventListener('change', function () {

    const optionValue = this.options[this.selectedIndex].dataset.option || '';
    optionSelect.innerHTML = '';
    notesInput.value = '';

    if (optionValue.trim() !== '') {
        optionValue.split(',').forEach(val => {
            const opt = document.createElement('option');
            opt.value = val.trim();
            opt.textContent = val.trim();
            optionSelect.appendChild(opt);
        });

        optionWrapper.style.display = 'block';
        notesInput.value = optionSelect.value; 
    } else {
        optionWrapper.style.display = 'none';
    }
});

optionSelect.addEventListener('change', function () {
    notesInput.value = this.value;
});
</script>


@endsection