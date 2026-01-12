<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;

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
    Route::get('/trx', [TransactionController::class, 'adminTransactions'])->name('trx');
    Route::get('/trx/all', [TransactionController::class, 'transactionAll'])->name('trx.all');
    Route::get('/users-management', [UserController::class, 'users'])->name('users.management');
    Route::get('/users-management/data', [UserController::class, 'users_data'])->name('users.management.data');
    Route::post('/users-management/update-role', [UserController::class, 'users_update_role'])->name('users.management.update.role');
});

// only for checker
Route::middleware(['role:checker'])->group(function () {
    Route::get('/gate-check', [CheckerController::class, 'showGateCheck'])->name('gate-check');
    Route::post('/gate-check/scan', [CheckerController::class, 'processScan'])->name('gate-check.scan');
});

// only for user
Route::middleware(['role:user,ots'])->group(function () {
    Route::get('/event/tickets/{id}', [EventController::class, 'event_tickets'])->name('event_tickets');
    Route::post('/event/tickets/add-to-cart', [EventController::class, 'event_tickets_add_to_cart'])->name('event_tickets.add_to_cart');

    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/cart/remove', [CartController::class, 'cart_remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'cart_checkout'])->name('cart.checkout');

    Route::get('/transactions', [TransactionController::class, 'transactions'])->name('transactions');

    Route::get('/tickets/my-tickets', [TicketController::class, 'my_tickets'])->name('tickets.my_tickets');
    Route::get('/tickets/my-tickets/data', [TicketController::class, 'my_tickets_data'])->name('tickets.my_tickets_data');
});

Route::middleware(['role:admin,checker'])->group(function () {
    Route::get('/ticket-presents', [DashboardController::class, 'present_ticket_data'])->name('present_ticket_data');
});
