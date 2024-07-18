<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositoTypeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('customers', UserController::class);
    Route::apiResource('deposito-types', DepositoTypeController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::post('accounts/{account}/deposit', [AccountController::class, 'deposit']);
    Route::post('accounts/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::get('customer/deposits', [AccountController::class, 'customerDeposits']);
    Route::get('customer/accounts', [AccountController::class, 'customerAccounts']);
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'show']);
});
