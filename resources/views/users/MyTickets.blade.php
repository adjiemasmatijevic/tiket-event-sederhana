@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'My Tickets')

@section('content')
<div class="container">
    <br>
    <div class="row">

        @forelse ($myTickets as $ticket)
        <div class="col-12">
            <div class="dz-order">

                <div class="order-info">
                    <div class="pe-2">
                        <span class="productId">#{{ $ticket->id_tiket }}</span>
                        <h6 class="title">
                            <a href="{{ route('event_tickets', $ticket->event_id) }}">{{ $ticket->event_name }}</a>
                        </h6>
                    </div>
                    <div class="media media-70">
                        <img src="/storage/event_images/{{ $ticket->event_image }}.webp" alt="{{ $ticket->event_name }}" />
                    </div>
                </div>

                <div class="order-detail">
                    <div class="d-flex gap-2">
                        <span>{{ $ticket->ticket_name }}</span>
                        @if($ticket->presence == 1)
                        <span class="badge bg-success ms-3">Attended</span>
                        @endif
                    </div>
                    <div class="d-flex gap-1 align-items-center mt-1">
                        <span class="text-muted">{{ $ticket->event_location }}</span>
                    </div>
                </div>

                <div class="status">
                    <button type="button" class="btn btn-sm btn-primary me-4" data-bs-toggle="modal" data-bs-target="#qrModal-{{ $ticket->id_tiket }}">
                        Show QR
                    </button>

                    <p class="mb-0 description">
                        Start: {{ \Carbon\Carbon::parse($ticket->events_time_start)->format('D, d M Y \a\t H:i') }}
                    </p>
                </div>

            </div>

            <div class="modal fade" id="qrModal-{{ $ticket->id_tiket }}" tabindex="-1" aria-labelledby="qrModalLabel-{{ $ticket->id_tiket }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qrModalLabel-{{ $ticket->id_tiket }}">Ticket QR Code</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="feather icon-x"></span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->id_tiket) }}" alt="QR Code for ticket {{ $ticket->id_tiket }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                You do not have any purchased tickets yet.
            </div>
        </div>
        @endforelse

    </div>
</div>
@endsection