@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Transaction')
@section('content')
<div class="row">
    <div class="col-12 my-4">

    </div>
</div>
<div class="container mt-4">
    <h3 class="fw-bold mb-3">transaction</h3>

    <div class="table-responsive">
        <table class="table align-middle" id="transactionTable">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>TDI Pay ID</th>
                    <th>Nama User</th>
                    <th>Total (IDR)</th>
                    <th>Status</th>
                    <th>Expired At</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody id="transactionBody">
                <tr><td colspan="7" class="text-center text-muted">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajax({
        url: "{{ route('trx.all') }}",
        method: "GET",
        success: function(data) {
            let rows = '';

            if (data.length === 0) {
                rows = `<tr><td colspan="7" class="text-center text-muted">Tidak ada data transaksi</td></tr>`;
            } else {
                data.forEach(t => {
                    let badgeClass = 'secondary';
                    if (t.status.toLowerCase() === 'success') badgeClass = 'success';
                    else if (t.status.toLowerCase() === 'pending') badgeClass = 'warning';
                    else if (t.status.toLowerCase() === 'canceled') badgeClass = 'danger';

                    rows += `
                        <tr>
                            <td>${t.no}</td>
                            <td><span class="fw-semibold">${t.tdi_pay_id}</span></td>
                            <td>${t.user_name}</td>
                            <td>IDR ${t.amount_total}</td>
                            <td>s

                            <td>${t.expired_at}</td>
                            <td>${t.created_at}</td>
                        </tr>
                    `;
                });
            }

            $('#transactionBody').html(rows);
        },
        error: function(xhr, status, error) {
            console.error(error);
            $('#transactionBody').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data transaksi.</td></tr>');
        }
    });
});
</script>
@endsection
