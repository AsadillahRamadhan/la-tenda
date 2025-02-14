<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAuthenticate;
use App\Http\Middleware\IsSuperAdmin;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(IsAuthenticate::class)->group(function () {
    Route::prefix('dashboard')->middleware(IsAdmin::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);

        Route::middleware(IsSuperAdmin::class)->group(function () {
            Route::resource('users', UserController::class);
        });
    });

    Route::get('/', [TransactionController::class, 'index'])->name('transaction');
    Route::post('/store_transaction', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('logout', [LoginController::class, 'logout'])->name('get_logout');
});
Auth::routes();
