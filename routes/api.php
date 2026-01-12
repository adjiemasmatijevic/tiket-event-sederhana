<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CheckerController;
use Illuminate\Support\Facades\Route;

Route::post('/transaction-update', [TransactionController::class, 'transaction_update'])->name('transaction.update');
Route::post('/gate-check/scan', [CheckerController::class, 'processScan'])->name('gate-check.scan');
