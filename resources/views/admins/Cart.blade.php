@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Cart Admin')
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-3 text-primary">Data Cart</h2>
    </div>
</div>
 <div class="form-group mb-3">
            <label for="example-select">Event</label>
           <select class="form-control" id="eventSelect">
                    <option value="">All Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
            </select>
        </div>
 <div class="card shadow">
    <div class="card-body">
        <h5 class="card-title">Data Cart Tiket</h5>
        <div class="col-md-12 col-xl-12 mb-3">
    </div>
        <div class="table-responsive">
            <table id="ticket-table"
                   class="table table-bordered table-striped datatables"
                   style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>NO</th>
                        <th>USER</th>
                        <th>TICKET</th>
                        <th>PAKET</th>
                        <th>PRESENSI</th>
                        <th>STATUS</th>
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
    fetch("{{ route('cart.data') }}")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#ticket-table tbody');
            let rows = '';

            if (data.length === 0) {
                rows = `
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada data
                        </td>
                    </tr>`;
            } else {
                data.forEach((item, index) => {
                    const presensi = item.presence == 1
                        ? `<button class="btn btn-md btn-success text-white fw-semibold shadow-sm" disabled>
                            Hadir
                           </button>`
                        : `<button class="btn btn-md btn-secondary text-white fw-semibold shadow-sm" disabled>
                            Belum Hadir
                           </button>`;
                    let btnClass = 'btn-secondary';
                    let btnText  = item.status;
                    let btnHref  = '#';
                    if (item.status === 'success') {
                        btnClass = 'btn-success';
                        btnText  = 'Success';
                    }
                    else if (item.status === 'checkout') {
                        btnClass = 'btn-warning text-dark';
                        btnText  = 'Checkout';
                    }

                    rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.user?.name ?? '-'}</td>
                            <td>${item.ticket?.name ?? '-'}</td>
                            <td>${item.notes ?? '-'}</td>
                            <td>${presensi}</td>
                            <td>
                                <a href="${btnHref}"
                                   class="btn btn-md ${btnClass} fw-semibold shadow-sm
                                   ${btnClass.includes('btn-warning') ? 'text-dark' : 'text-white'}">
                                    ${btnText}
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }

            tbody.innerHTML = rows;

            if (!$.fn.DataTable.isDataTable('#ticket-table')) {
                $('#ticket-table').DataTable();
            }
        })
        .catch(() => {
            document.querySelector('#ticket-table tbody').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">
                        Gagal memuat data
                    </td>
                </tr>`;
        });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    let tableInitialized = false;

    function loadTickets(eventId = '') {
        let url = "{{ route('cart.data') }}";
        if (eventId) {
            url += `?event_id=${eventId}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#ticket-table tbody');
                let rows = '';

                if (data.length === 0) {
                    rows = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>`;
                } else {
                    data.forEach((item, index) => {

                        const presensi = item.presence == 1
                            ? `<button class="btn btn-md btn-success text-white fw-semibold shadow-sm" disabled>
                                Hadir
                               </button>`
                            : `<button class="btn btn-md btn-secondary text-white fw-semibold shadow-sm" disabled>
                                Belum Hadir
                               </button>`;

                        let btnClass = 'btn-secondary';
                        let btnText  = item.status;

                        if (item.status === 'success') {
                            btnClass = 'btn-success';
                            btnText  = 'Success';
                        } else if (item.status === 'checkout') {
                            btnClass = 'btn-warning text-dark';
                            btnText  = 'Checkout';
                        }

                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.user?.name ?? '-'}</td>
                                <td>${item.ticket?.name ?? '-'}</td>
                                <td>${item.notes ?? '-'}</td>
                                <td>${presensi}</td>
                                <td>
                                    <span class="btn btn-md ${btnClass} fw-semibold shadow-sm
                                        ${btnClass.includes('btn-warning') ? 'text-dark' : 'text-white'}">
                                        ${btnText}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                }

                tbody.innerHTML = rows;

                if (!tableInitialized) {
                    $('#ticket-table').DataTable();
                    tableInitialized = true;
                }
            })
            .catch(() => {
                document.querySelector('#ticket-table tbody').innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Gagal memuat data
                        </td>
                    </tr>`;
            });
    }

    loadTickets();
    document.getElementById('eventSelect').addEventListener('change', function () {
        loadTickets(this.value);
    });

});
</script>


@endsection


