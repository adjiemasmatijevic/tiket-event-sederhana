@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Cart Admin')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-1 text-primary">Data Carts</h2>
        <p class="mb-3 text-dark">manage cart information</p>
    </div>
</div>
 <div class="form-group mb-3">
            <label for="example-select">Events</label>
           <select class="form-control" id="eventSelect">
                    <option value="">All Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
            </select>
        </div>
 <div class="card shadow">
    <div class="card-body">
        <h5 class="card-title">Data Cart Tikets</h5>
        <div class="col-md-12 col-xl-12 mb-3">
    </div>
        <div class="table-responsive">
            <table id="ticket-table"
                   class="table table-bordered table-striped datatables text-center"
                   style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>NO</th>
                        <th>USER</th>
                        <th>TICKET</th>
                        <th>PAKET</th>
                        <th>PRESENSI</th>
                        <th>STATUS</th>
                        <th>STATUS TRX</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let table = $('#ticket-table').DataTable({
        searching: true,
        paging: true,
        ordering: true
    });

    function loadTickets(eventId = '') {
        let url = "{{ route('cart.data') }}";
        if (eventId) {
            url += `?event_id=${eventId}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {

                table.clear(); 

                if (data.length === 0) {
                    table.draw();
                    return;
                }

                data.forEach((item, index) => {

                    const presensi = item.presence == 1
                        ? `<button class="btn btn-md btn-success text-white fw-semibold shadow-sm" disabled>Hadir</button>`
                        : `<button class="btn btn-md btn-secondary text-white fw-semibold shadow-sm" disabled>Belum Hadir</button>`;

                    let btnClass = 'btn-secondary';
                    let btnText  = item.status;

                    if (item.status === 'success') {
                        btnClass = 'btn-success';
                        btnText  = 'Success';
                    } else if (item.status === 'checkout') {
                        btnClass = 'btn btn-md btn-warning text-white fw-semibold shadow-sm';
                        btnText  = 'Checkout';
                    }

                    let trxStatus = item.transaction?.status ?? '-';
                    let trxBadge = trxStatus === 'success'
                        ? `<span class="btn btn-md btn-success text-white fw-semibold shadow-sm">Success</span>`
                        : `<span class="btn btn-md btn-secondary text-white fw-semibold shadow-sm">${trxStatus}</span>`;

                    table.row.add([
                        index + 1,
                        item.user?.name ?? '-',
                        item.ticket?.name ?? '-',
                        item.notes ?? '-',
                        presensi,
                        `<span class="btn ${btnClass} btn-sm">${btnText}</span>`,
                        trxBadge
                    ]);
                });

                table.draw();
            })
            .catch(() => {
                table.clear().draw();
            });
    }

    loadTickets();

    document.getElementById('eventSelect').addEventListener('change', function () {
        loadTickets(this.value);
    });

});
</script>


@endsection


