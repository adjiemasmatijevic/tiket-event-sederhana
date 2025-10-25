@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'Dashboard')

@section('content')
<h2 class="page-title text-primary">Dashboard</h2>
<p class="text-dark">Welcome to {{ config('app.name') }} user dashboard</p>
@endsection