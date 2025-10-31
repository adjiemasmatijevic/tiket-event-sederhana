@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Transaction')
@section('content')
<div class="my-4">
    <h2 class="h3 mb-1 text-primary">Transactions</h2>
    <p class="mb-3 text-dark">All Transactions</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped datatables" id="transactionTable">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
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
                    const formatText = (text, length = 20) => {
                            if (!text) return '';
                            return text.toString().toUpperCase().substring(0, length);
                        };
                    rows += `
                        <tr>
                            <td>${t.no}</td>
                            <td><span class="fw-semibold">#${formatText(t.trx_id, 8)}</span></td>
                            <td>${t.user_name}</td>
                            <td>IDR ${t.amount_total}</td>
                            <td>
                                <span class="badge bg-${badgeClass} text-white px-3 py-2 fs-6 fw-semibold text-uppercase shadow-sm">
                                    ${t.status}
                                </span>
                            </td>
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
