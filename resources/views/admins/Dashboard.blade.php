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
                        <span class="circle circle-sm bg-warning-light">
                            <i class="fe fe-tag fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Tickets Sold</p>
                        <span class="h3 mb-0">{{ $ticketsSold ?? 0 }}</span>
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
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-dollar-sign fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Gross Revenue</p>
                        <span class="h3 mb-0">IDR {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
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
                        <span class="circle circle-sm bg-info-light">
                            <i class="fe fe-dollar-sign fe-16 text-white"></i>
                        </span>
                    </div>
                    <div class="col pr-0">
                        <p class="small text-dark mb-0">Net Revenue</p>
                        <span class="h3 mb-0">IDR {{ number_format($net ?? 0, 0, ',', '.') }}</span>
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
</div>
@endsection