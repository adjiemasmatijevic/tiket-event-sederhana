<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;

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

// only for admin
Route::middleware(['role:admin'])->group(function () {
    Route::get('/events', [EventController::class, 'events'])->name('events');
    Route::get('/events/data', [EventController::class, 'events_data'])->name('events.data');
    Route::get('/events/data/{id}', [EventController::class, 'events_data_id'])->name('events.data.id');
    Route::post('/events/create', [EventController::class, 'events_create'])->name('events.create');
    Route::post('/events/update', [EventController::class, 'events_update'])->name('events.update');
    Route::post('/events/delete', [EventController::class, 'events_delete'])->name('events.delete');

    Route::get('/tickets', [TicketController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/data', [TicketController::class, 'tickets_data'])->name('tickets.data');
    Route::get('/tickets/data/{id}', [TicketController::class, 'tickets_data_id'])->name('tickets.data.id');
    Route::post('/tickets/create', [TicketController::class, 'tickets_create'])->name('tickets.create');
    Route::post('/tickets/update', [TicketController::class, 'tickets_update'])->name('tickets.update');
    Route::post('/tickets/delete', [TicketController::class, 'tickets_delete'])->name('tickets.delete');
});
