@extends('templates.authentication')

@section('app_name', config('app.name'))
@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login.handle') }}" class="col-lg-3 col-md-4 col-10 mx-auto text-center">
    @csrf
    <img src="{{ asset('assets/images/logo.webp') }}" alt="logo" width="100" class="mb-3" loading="lazy">
    <p class="mb-4 text-dark">please login to access more features</p>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="form-group mb-3">
        <label for="email" class="sr-only">Email address</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg"
            placeholder="Email" required autofocus value="{{ old('email') }}">
    </div>

    <div class="form-group mb-3">
        <label for="password" class="sr-only">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" class="form-control form-control-lg"
                placeholder="Password" required value="{{ old('password') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <button class="btn btn-lg btn-primary btn-block mb-2" type="submit">Sign in</button>
    <a href="#" onclick="history.back()" class="btn btn-lg btn-light btn-block mb-3">Back</a>

    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('forgot.password') }}" class="btn btn-link">Forgot password</a>

        <div class="d-flex justify-content-center align-items-center">
            <p class="mb-0 mt-0 text-dark mr-2">No account?</p>
            <a href="{{ route('register') }}" class="text-primary mb-0 mt-0">Register</a>
        </div>
    </div>
</form>
@endsection