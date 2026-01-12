@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Transaction')
@section('content')
<div class="my-4">
    <h2 class="h3 mb-1 text-primary">Transactions</h2>
    <p class="mb-3 text-dark">All Transactions</p>
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
    <div class="table-responsive">
        <table class="table table-bordered table-striped datatables" id="transactionTable">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>User Detail</th>
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
    function loadTransactions(eventId = '') {
        $.ajax({
            url: "{{ route('trx.filter') }}", 
            method: "GET",
            data: {
                event_id: eventId
            },
            success: function(data) {
                let rows = '';
                if (data.length === 0) {
                    rows = `<tr>
                        <td colspan="7" class="text-center text-muted">
                            Tidak ada data transaksi
                        </td>
                    </tr>`;
                } else {
                    data.forEach(t => {
                        let btnClass = 'btn-secondary';
                        let btnText = '';
                        let btnHref = '#';
                        const formatText = (text, length = 20) => {
                            if (!text) return '';
                            return text.toString().toUpperCase().substring(0, length);
                        };
                        const formatPhone = (phone) => {
                            if (!phone) return '';
                            phone = phone.toString().trim();
                            if (phone.startsWith('0')) return '62' + phone.substring(1);
                            if (phone.startsWith('+62')) return phone.replace('+', '');
                            return phone;
                        };
                        const phoneNumber = formatPhone(t.phone);
                        if (t.status === 'success') {
                            btnClass = 'btn-success';
                            btnText = 'Success';
                        } 
                        else if (t.status === 'pending') {
                            btnClass = 'btn-warning text-dark';
                            btnText = 'Kirim Tagihan';
                            const msg = encodeURIComponent(
                                `Hai ${t.user_name}, Terima kasih atas pesanan Anda! ` +
                                `Invoice Anda #${formatText(t.trx_id, 8)} sebesar Rp${t.amount_total}. ` +
                                `Silakan selesaikan pembayaran melalui https://ticket.spasicreative.space/transactions`
                            );
                            btnHref = `https://wa.me/${phoneNumber}?text=${msg}`;
                        } 
                        else if (t.status === 'canceled') {
                            btnClass = 'btn-danger';
                            btnText = 'Canceled';
                        } 
                        else {
                            btnText = formatText(t.status);
                        }
                        rows += `
                            <tr>
                                <td>${t.no}</td>
                                <td>
                                    <span class="fw-semibold">
                                        #${formatText(t.trx_id, 8)}
                                    </span>
                                </td>
                                <td>
                                    ${t.user_name}<br>
                                    ${t.phone}
                                </td>
                                <td>IDR ${t.amount_total}</td>
                                <td>
                                    <a href="${btnHref}" target="_blank"
                                        class="btn btn-md ${btnClass} text-white fw-semibold shadow-sm">
                                        ${btnText}
                                    </a>
                                </td>
                                <td>${t.expired_at}</td>
                                <td>${t.created_at}</td>
                            </tr>
                        `;
                    });
                }
                $('#transactionBody').html(rows);
            },
            error: function() {
                $('#transactionBody').html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Gagal memuat data transaksi
                        </td>
                    </tr>
                `);
            }
        });
    }
    loadTransactions();
    $('#eventSelect').on('change', function () {
        loadTransactions($(this).val());
    });

});
</script>

@endsection
