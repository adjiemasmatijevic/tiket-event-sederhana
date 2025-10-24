<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// fallback route
Route::fallback(function () {
    return redirect()->route('home');
});

// landing page
Route::get('/', [LandingController::class, 'home'])->name('home');

// only for guest without session
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login_handle'])->name('login.handle');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register_handle'])->name('register.handle');

    Route::get('/forgot-password', [ResetPasswordController::class, 'RequestReset'])->name('forgot.password');
    Route::post('/forgot-password', [ResetPasswordController::class, 'SendResetLink'])->name('forgot.password.handle');
    Route::get('/reset-password/{id}', [ResetPasswordController::class, 'ResetPassword'])->name('reset.password');
    Route::post('/reset-password', [ResetPasswordController::class, 'UpdatePassword'])->name('reset.password.handle');
});

// only for authenticated user with session
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/update-photo', [ProfileController::class, 'update_photo'])->name('update.photo');
    Route::post('/update-profile', [ProfileController::class, 'update_profile'])->name('update.profile');
    Route::post('/change-password', [ProfileController::class, 'change_password'])->name('change.password');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
