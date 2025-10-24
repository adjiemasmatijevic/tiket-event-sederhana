@extends('templates.authentication')

@section('app_name', config('app.name'))
@section('title', 'Reset Password')

@section('content')
<form action="{{ route('reset.password.handle') }}" method="POST" class="p-4 bg-white rounded shadow shadow-sm" style="width: 400px; max-width: 100%;">
    @csrf
    @if (session('success'))
    <div class="mb-3 alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-3 alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="text-center">
        <a href="/" class="d-block mb-3">
            <img src="/assets/images/logo.webp" alt="logo" class="img-fluid w-50" loading="lazy">
        </a>
        <h2 class="mb-3 text-primary">Reset Password</h2>
    </div>

    <input type="hidden" name="reset_id" id="reset_id" value="{{ $reset->id }}">

    <div class="form-group mb-3">
        <div class="input-group">
            <input type="password" class="form-control form-control-lg" name="NewPassword" id="NewPassword" placeholder="New Password" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordR">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>
        @error('NewPassword')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="form-group mb-3">
        <div class="input-group">
            <input type="password" class="form-control form-control-lg" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirm Password" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordRC">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>
        @error('ConfirmPassword')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary w-100 btn-lg" type="submit">
        Reset Password
    </button>
</form>
@endsection