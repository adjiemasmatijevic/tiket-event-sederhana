@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Change Password')

@section('content')
<div class="container py-5">

     @if (session('success'))
<div id="toastSuccess" class="alert alert-success fs-5">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div id="toastError" class="alert alert-danger fs-5">
    {{ session('error') }}
</div>
@endif

    <h2 class="mb-3 page-title text-primary">Change Password</h2>

    <form method="POST" action="{{ route('change.password') }}">
        @csrf

        <div class="row mb-4">
            <div class="col-md-6">

                <div class="form-group position-relative mb-2">
                    <label for="CurrentPassword">Current Password</label>
                    <input type="password" class="form-control" name="CurrentPassword" id="CurrentPassword" placeholder="Your current password" required>
                    <span class="toggle-password" toggle="#CurrentPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <div class="form-group position-relative mb-2">
                    <label for="NewPassword">New Password</label>
                    <input type="password" class="form-control" name="NewPassword" id="NewPassword" placeholder="Your new password" required value="{{ old('NewPassword') }}">
                    <span class="toggle-password" toggle="#NewPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <div class="form-group position-relative mb-2">
                    <label for="ConfirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirm your new password" required value="{{ old('ConfirmPassword') }}">
                    <span class="toggle-password" toggle="#ConfirmPassword" style="position:absolute; right:15px; top:38px; cursor:pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

            </div>

            <div class="col-md-6">
                <p class="mb-2"><strong>Password requirements</strong></p>
                <p class="small text-dark mb-2">
                    To create a new password, you have to meet all of the following requirements:
                </p>
                <ul class="small text-dark pl-4 mb-0">
                    <li>Minimum 8 characters</li>
                    <li>At least one uppercase character</li>
                    <li>At least one lowercase character</li>
                    <li>At least one special character</li>
                    <li>At least one number</li>
                </ul>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>

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
