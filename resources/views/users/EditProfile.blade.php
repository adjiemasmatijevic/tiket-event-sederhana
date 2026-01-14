@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">

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

    <h2 class="mb-3 page-title text-primary">Edit Profile</h2>

    <form method="POST" action="{{ route('update.profile') }}">
        @csrf

        <div class="row">

            <div class="col-md-6 mb-2">
                <label>Name</label>
                <input class="form-control" name="name" value="{{ $user->name }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>Gender</label>
                <select class="form-control" name="gender">
                    <option value="male" {{ $user->gender=='male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender=='female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="col-md-6 mb-2">
                <label>Phone</label>
                <input class="form-control" name="phone" value="{{ $user->phone }}">
            </div>

            <div class="col-md-6 mb-2">
                <label>Email</label>
                <input class="form-control" name="email" value="{{ $user->email }}">
            </div>

            <div class="col-12 mb-3">
                <label>Address</label>
                <textarea class="form-control" name="address" rows="3">{{ $user->address }}</textarea>
            </div>

        </div>

        <button type="submit" class="btn btn-primary w-100">Save</button>

    </form>

</div>
@endsection
