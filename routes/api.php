<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\Api\SavingGoalApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// 1. Endpoint Transaksi
Route::get('/transactions', [TransactionApiController::class, 'index']);
Route::post('/transactions', [TransactionApiController::class, 'store']);
Route::delete('/transactions/{id}', [TransactionApiController::class, 'destroy']);

// 2. Endpoint Akun (Accounts)
Route::get('/accounts', [AccountApiController::class, 'index']);
Route::post('/accounts', [AccountApiController::class, 'store']);

// 3. Endpoint Target Tabungan (Saving Goals)
Route::get('/saving-goals', [SavingGoalApiController::class, 'index']);
Route::post('/saving-goals', [SavingGoalApiController::class, 'store']);
