<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepositoTypeController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('deposito-types', DepositoTypeController::class);
    Route::get('accounts', [AccountController::class, 'index']);
    Route::post('/accounts/create', [AccountController::class, 'store']);
    Route::post('accounts/{account}/deposit', [AccountController::class, 'deposit']);
    Route::post('accounts/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::get('accounts/transactions/{account}', [AccountController::class, 'transaction']);
    Route::get('customer/deposits', [AccountController::class, 'customerDeposits']);
    Route::get('customer/accounts', [AccountController::class, 'customerAccounts']);
    Route::get('accounts/{account}/transactions', [TransactionController::class, 'index']);
});
