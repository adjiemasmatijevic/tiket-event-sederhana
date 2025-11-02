@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Ticket OTS')

@section('content')
@if (session('payment'))
<script>
    window.location = "{{ session('payment') }}";
</script>
@endif

<div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-1 text-primary">Ticket OTS</h2>
        <p class="mb-3 text-dark">Manage selling tickets on the spot</p>

        @if (session('success'))
        <div class="mb-3 alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="mb-3 alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Penjualan On The Spot</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('ticket_ots.create') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Nama Pembeli <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="phone">Nomor Telepon (WA) <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 085969XXXXX" required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="ticket_id">Pilih Event - Jenis Tiket <span class="text-danger">*</span></label>
                            <select name="ticket_id" id="ticket_id" class="form-control @error('ticket_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Event dan Tiket --</option>
                                @foreach($tickets as $ticket)
                                <option
                                    value="{{ $ticket->id }}"
                                    data-event="{{ $ticket->event_name }}"
                                    data-price="{{ $ticket->price }}"
                                    data-total="{{ $ticket->total }}"
                                    {{ $ticket->total <= 0 ? 'disabled' : '' }}
                                    {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                    {{ $ticket->event_name }} - {{ $ticket->name }} (Harga: {{ number_format($ticket->price, 0, ',', '.') }})
                                    {{ $ticket->total <= 0 ? ' (HABIS)' : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('ticket_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group">
                            <label class="d-block">Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="cash">Cash (Tunai)</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris" {{ old('payment_method') == 'qris' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="qris">QRIS</label>
                            </div>
                            @error('payment_method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Penjualan</button>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="user-table" class="table table-bordered table-striped datatables" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>NO</th>
                                <th>NAME</th>
                                <th>PHONE</th>
                                <th>EVENT</th>
                                <th>TICKET</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('ticket_ots.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ots_name',
                    name: 'ots.name',
                    title: 'NAME'
                },
                {
                    data: 'phone',
                    name: 'ots.phone',
                    title: 'PHONE'
                },
                {
                    data: 'event_name',
                    name: 'events.name',
                    title: 'EVENT'
                },
                {
                    data: 'ticket_name',
                    name: 'tickets.name',
                    title: 'TICKET'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [3, 'asc']
            ]
        });
    });
</script>
@endsection