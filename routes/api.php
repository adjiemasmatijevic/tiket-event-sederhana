<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/transaction-update', [TransactionController::class, 'transaction_update'])->name('transaction.update');
