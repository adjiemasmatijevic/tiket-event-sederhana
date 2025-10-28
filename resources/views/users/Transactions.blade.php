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
                    @foreach($transactions as $transaction)
                    <tr>
                        <td style="padding:0">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item bg-white shadow rounded">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse-{{ $loop->iteration }}"
                                            aria-expanded="false" aria-controls="panelsStayOpen-collapse-{{ $loop->iteration }}">
                                            <h6 style="font-size:14px; margin-bottom:0px">{{ substr(strtoupper($transaction->id), 0, 8) }}
                                            </h6><br>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse-{{ $loop->iteration }}" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <ul class="dz-list message-list" style="margin-bottom: -20px;">
                                                <li>
                                                    <a href="https://payment.talangdigital.com/transaction-detail/{{ $transaction->tdi_pay_id }}" target="_blank">
                                                        <div class="media-content">
                                                            <div>
                                                                <h6 class="name">Rp. {{ number_format($transaction->amount_total, 0, ',', '.') }}</h6>
                                                                <p class="my-1">
                                                                    <b>CREATED AT :</b> {{ \Carbon\Carbon::parse($transaction->created_at)->format('D, d M Y \a\t H:i') }} <br>
                                                                    <b>EXPIRED AT :</b> {{ \Carbon\Carbon::parse($transaction->expired_at)->format('D, d M Y \a\t H:i') }} <br>
                                                                    <b>STATUS :</b> {{ $transaction->status }} <br>
                                                                </p>
                                                            </div>
                                                            <div class="left-content">
                                                                @if($transaction->status == 'success')
                                                                <div class="seen-btn active mt-2 dz-flex-box">
                                                                    <i class="icon feather icon-check"></i>
                                                                </div>
                                                                @elseif($transaction->status == 'pending')
                                                                <div class="seen-btn mt-2 dz-flex-box"
                                                                    style="background: #FFD700; border-color: #FFD700;">
                                                                    <i class="icon feather icon-clock text-dark"></i>
                                                                </div>
                                                                @elseif($transaction->status == 'canceled')
                                                                <div class="seen-btn mt-2 dz-flex-box"
                                                                    style="background: #FF4500; border-color: #FF4500;">
                                                                    <i class="icon feather icon-x text-white"></i>
                                                                </div>
                                                                @endif
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        new DataTable('#example');
    </script>
</div>
@endsection