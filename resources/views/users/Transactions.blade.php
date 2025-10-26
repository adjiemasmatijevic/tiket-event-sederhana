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

            <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModalLabel" aria-hidden="true">
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
                                <button type="button" class="btn mb-2 btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn mb-2 btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transaction-table" class="table table-bordered table-striped datatables" style="width:100%">
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

    <script>
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