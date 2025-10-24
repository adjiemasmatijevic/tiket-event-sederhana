@extends('layouts.dashboard_template')

@section('title', 'Profile')

@section('content')
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
<div class="d-flex flex-column align-items-center justify-content-center">
    <!-- Foto profil -->
    <style>
        .profile-container {
            position: relative;
            width: 150px;
            height: 150px;
            cursor: pointer;
        }

        .profile-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .profile-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s;
            text-align: center;
        }

        .profile-container:hover .profile-overlay {
            opacity: 1;
        }
    </style>

    <div class="profile-container" onclick="document.getElementById('photoInput').click();">
        <img src="/storage/profile_pictures/{{ $user->profile_picture }}.webp" alt="Profile Photo" loading="lazy">
        <div class="profile-overlay">Change</div>
    </div>

    <form id="photoForm" action="{{ route('update.photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="file" name="photo" id="photoInput" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="document.getElementById('photoForm').submit();">
    </form>

    <!-- Info kategori -->
    <p class="h2 mt-4 mb-2 font-weight-bold text-dark text-uppercase">{{ $user->role }}</p>
    @if ($user->role == 'premium')
    <p class="text-dark">
        expires on: {{ \Carbon\Carbon::parse($user->premium_expired_date)->format('d/m/Y') }}
    </p>
    @endif
</div>

<form method="POST" action="{{ route('update.profile') }}" class="text-dark">
    @csrf
    <h2 class="h3 mb-4 page-title text-primary">Profile</h2>
    <hr class="my-4">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="name" class="form-control" name="name" id="name" placeholder="Your full name" value="{{ $user->name }}" required>
        </div>
        <div class="form-group col-md-6">
            <label for="gender">Gender</label>
            <select class="form-control" name="gender" id="gender" required>
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="phone">Phone</label>
            <div class="input-group">
                <select class="form-control" name="phone_code" id="phone_code" style="max-width: 100px;">
                    <option value="62" selected>+62</option>
                </select>
                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Your phone number" value="{{ $user->phone_number }}" required>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Your email address" value="{{ $user->email }}" required>
        </div>
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control" placeholder="Your address" required>{{ $user->address }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<form method="POST" action="{{ route('change.password') }}" class="mt-5 mb-5 text-dark">
    @csrf
    <h2 class="h3 mt-4 page-title text-primary">Change Password</h2>
    <hr class="my-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group position-relative">
                <label for="CurrentPassword">Current Password</label>
                <input type="password" class="form-control" name="CurrentPassword" id="CurrentPassword" placeholder="Your current password" required>
                <span class="toggle-password" toggle="#CurrentPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <div class="form-group position-relative">
                <label for="NewPassword">New Password</label>
                <input type="password" class="form-control" name="NewPassword" id="NewPassword" placeholder="Your new password" required>
                <span class="toggle-password" toggle="#NewPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
            <div class="form-group position-relative">
                <label for="ConfirmPassword">Confirm Password</label>
                <input type="password" class="form-control" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirm your new password" required>
                <span class="toggle-password" toggle="#ConfirmPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <p class="mb-2">Password requirements</p>
            <p class="small text-dark mb-2"> To create a new password, you have to meet all of the following requirements: </p>
            <ul class="small text-dark pl-4 mb-0">
                <li>Minimum 8 character</li>
                <li>At least one uppercase character</li>
                <li>At least one lowercase character</li>
                <li>At least one special character</li>
                <li>At least one number</li>
            </ul>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Change Password</button>
</form>

<script>
    document.querySelectorAll(".toggle-password").forEach(function(toggle) {
        toggle.addEventListener("click", function() {
            const input = document.querySelector(this.getAttribute("toggle"));
            const icon = this.querySelector("i");
            if (input.getAttribute("type") === "password") {
                input.setAttribute("type", "text");
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.setAttribute("type", "password");
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    });
</script>
@endsection