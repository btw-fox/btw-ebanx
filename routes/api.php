<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/reset', [PaymentController::class, 'reset'])->name('payment.reset');
Route::post('/index', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/balance', [PaymentController::class, 'balance'])->name('payment.balance');
Route::post('/event', [PaymentController::class, 'event'])->name('payment.event');
