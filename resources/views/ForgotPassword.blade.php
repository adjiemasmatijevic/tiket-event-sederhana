@extends('templates.authentication')

@section('app_name', config('app.name'))
@section('title', 'Forgot Password')

@section('content')
<form action="{{ route('forgot.password.handle') }}" method="POST" class="text-center p-4 bg-white rounded shadow-sm">
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

    <a href="/" class="d-block mb-3">
        <img src="/assets/images/logo.webp" alt="logo" class="img-fluid w-50" loading="lazy">
    </a>
    <h2 class="mb-3 text-primary">Forgot Password</h2>
    <p class="text-dark mb-4">
        Enter your email address and we'll send you instructions to reset your password
    </p>

    <div class="mb-3">
        <input type="email" id="inputEmail" name="email"
            class="form-control form-control-lg"
            placeholder="Email address" required autofocus value="{{ old('email') }}">
        @error('email')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <button class="btn btn-primary w-100 btn-lg mb-2" type="submit">
        Request Reset Password
    </button>
    <a href="#" onclick="history.back()" class="btn btn-lg btn-light btn-block mb-3">Back</a>

</form>
@endsection