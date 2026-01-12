@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Analytics Dashboard')

@section('content')
<div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-3 text-primary">Analytics Dashboard</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-primary-light">
                            <i class="fe fe-calendar fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Total Events</p>
                        <span class="h3 mb-0">{{ $totalEvents }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success-light">
                            <i class="fe fe-activity fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Active Events</p>
                        <span class="h3 mb-0">{{ $activeEvents }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-secondary-light">
                            <i class="fe fe-users fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Total Users</p>
                        <span class="h3 mb-0">{{ $totalUsers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-secondary-light">
                            <i class="fe fe-user-check fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Total Checkers</p>
                        <span class="h3 mb-0">{{ $totalCheckers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-12 mb-3">
        <div class="form-group mb-3">
            <label for="example-select">Event</label>
           <select class="form-control" id="eventSelect">
                    <option value="">All Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
            </select>

        </div>
    </div>
    <div class="col-md-6 col-xl-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning-light">
                            <i class="fe fe-tag fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Tickets Sold</p>
                        <span id="ticketsSold" class="h3 mb-0">{{ $ticketsSold ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-activity fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                     <p class="small text-dark mb-0">Ticket Checked</p>
                     <span class="h3 mb-0" id="ticketsChecked">{{ $ticketsPresent }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-trending-up fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Gross Revenue</p>
                        <span id="grossRevenue" class="h3 mb-0">
                            IDR {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-trending-up fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                       <p class="small text-dark mb-0">Net Revenue</p>
                        <span id="netRevenue" class="h3 mb-0">
                            IDR {{ number_format($net ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-trending-up fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Fee Admin</p>
                    <span id="feeAdmin" class="h3 mb-0">
                        IDR {{ number_format($feeAdmin ?? 0, 0, ',', '.') }}
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow">
    <div class="card-body">
        <h5 class="card-title">Ticket Checked</h5>
        <div class="table-responsive">
            <table id="ticket-table" class="table table-bordered table-striped datatables" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>NO</th>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>EVENT</th>
                        <th>TIKET</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(function () {

    let table = $('#ticket-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('present_ticket_data') }}",
            data: function (d) {
                d.event_id = $('#eventSelect').val(); // ⬅️ kirim event_id
            }
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            { data: 'id_tiket', name: 'carts.id' },
            { data: 'user_name', name: 'users.name' },
            { data: 'event_name', name: 'events.name' },
            { data: 'ticket_name', name: 'tickets.name' },
        ],
        order: [[3, 'asc']]
    });

    // 🔥 Trigger filter
    $('#eventSelect').on('change', function () {
        table.ajax.reload(); // reload table
    });

});
</script>

<script>
$('#eventSelect').on('change', function () {
    let eventId = $(this).val();

    $.ajax({
        url: '/filter',
        type: 'GET',
        data: { event_id: eventId },
        success: function (res) {
            $('#ticketsSold').text(res.tickets_sold);
            $('#ticketsChecked').text(res.tickets_checked);
            $('#grossRevenue').text(res.gross_revenue);
            $('#netRevenue').text(res.net_revenue);
            $('#feeAdmin').text(res.fee_admin);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Gagal memuat data dashboard');
        }
    });
});
</script>
@endsection