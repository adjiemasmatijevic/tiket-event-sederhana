@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Transcation')

@section('content')
<div class="container">

    <div class="row my-2">
        <div class="col-12">
            <h2 class="h3 mb-1 text-primary">Transactions</h2>
            <p class="mb-3 text-dark">Manage your transactions here</p>
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

            <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="DeleteModalLabel">Cancel Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <form action="{{ route('transactions.cancel') }}" method="post">
                            <div class="modal-body text-dark">
                                @csrf
                                <input type="hidden" id="delete_id" name="id">
                                <p>Are you sure you want to cancel this transaction?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn mb-2 btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn mb-2 btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transaction-table" class="table datatables" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>NO</th>
                                    <th>CREATED AT</th>
                                    <th>AMOUNT TOTAL</th>
                                    <th>STATUS</th>
                                    <th>EXPIRED AT</th>
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
    <div class="row mt-2">
        <div class="col-lg-12">
            <table id="example" class="table">
                <thead>
                    <tr>
                        <th class="text-primary border-0">
                            <center>______</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:0">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item bg-white shadow rounded">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-1"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapse-1">
                                            <h6 style="font-size:14px; margin-bottom:0px">#TRX_ID trim (8 Digit awal)
                                            </h6><br>

                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse-1" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <ul class="dz-list message-list" style="margin-bottom: -20px;">
                                                <li>
                                                    <a href="chat.html">
                                                        <div class="media-content">
                                                            <div>
                                                                <h6 class="name">Rp102.500</h6>
                                                                <p class="my-1">
                                                                    <b>CREATED AT :</b> 2025-10-26 23:26:37 <br>
                                                                    <b>EXPIRED AT :</b> 2025-10-27 23:26:37 <br>
                                                                    <b>STATUS :</b> Pending <br>
                                                                </p>
                                                            </div>
                                                            <div class="left-content">
                                                                <div class="seen-btn mt-2 dz-flex-box"
                                                                    style="background: #FFD700; border-color: #FFD700;">
                                                                    <i class="icon feather icon-clock text-dark"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item bg-white shadow rounded">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-2"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapse-2">
                                            <h6 style="font-size:14px; margin-bottom:0px">#TRX_ID trim (8 Digit awal)
                                            </h6><br>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse-2" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <ul class="dz-list message-list" style="margin-bottom: -30px;">
                                                <li>
                                                    <a href="chat.html">
                                                        <div class="media-content">
                                                            <div>
                                                                <h6 class="name">Rp52.500</h6>
                                                                <p class="my-1">
                                                                    <b>CREATED AT :</b> 2025-10-26 23:26:37 <br>
                                                                    <b>EXPIRED AT :</b> 2025-10-27 23:26:37 <br>
                                                                    <b>STATUS :</b> Success <br>
                                                                </p>
                                                            </div>
                                                            <div class="left-content">
                                                                <div class="seen-btn active mt-2 dz-flex-box">
                                                                    <i class="icon feather icon-check"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
    new DataTable('#example');
    $(function() {
        $('#transaction-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('transactions.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'amount_total',
                    name: 'amount_total'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'expired_at',
                    name: 'expired_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [3, 'asc']
            ]
        });
    });

    $(document).on('click', '[data-bs-target="#DeleteModal"]', function() {
        let id = $(this).data('bs-id');
        $('#delete_id').val(id);
    });
    </script>
</div>
@endsection