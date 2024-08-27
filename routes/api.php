<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateApiKey;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/somar', [PaymentController::class, 'somar'])->name('payment.somar');
Route::get('/resultado', [PaymentController::class, 'resultado'])->name('payment.resultado');



Route::post('/reset', [PaymentController::class, 'reset'])->name('payment.reset');
Route::post('/index', [PaymentController::class, 'index'])->name('payment.index');
Route::get('/balance', [PaymentController::class, 'balance'])->name('payment.balance');
Route::post('/event', [PaymentController::class, 'event'])->name('payment.event');
