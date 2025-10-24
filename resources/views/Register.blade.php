@extends('templates.authentication')

@section('app_name', config('app.name'))
@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register.handle') }}" class="col-lg-6 col-md-6 col-10 mx-auto">
    @csrf
    <div class="text-center">
        <img src="{{ asset('assets/images/logo.webp') }}" alt="logo" width="100" class="mb-3" loading="lazy">
        <h1 class="h1 mb-0 text-primary">Rempah Tour</h1>
        <p class="mb-4 text-dark">please login to access more features</p>
    </div>

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

    <div class="form-row">
        <div class="form-group col-md-6 mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Your full name" required value="{{ old('name') }}">
        </div>
        <div class="form-group col-md-6 mb-3">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender" id="gender" required>
                <option value="" selected disabled hidden class="text-muted">Select Gender</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6 mb-3">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Your phone number" required value="{{ old('phone') }}">
        </div>
        <div class="form-group col-md-6 mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Your email address" required value="{{ old('email') }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6 mb-3">
            <label for="password">Password</label>
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
        <div class="form-group col-md-6 mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg"
                    placeholder="Confirm Password" required value="{{ old('password_confirmation') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordC">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control" placeholder="Your address" required>{{ old('address') }}</textarea>
    </div>

    <button type="submit" class="btn btn-lg btn-primary w-100">Register</button>

    <p class="mt-4 text-dark">
        Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
    </p>
</form>
@endsection