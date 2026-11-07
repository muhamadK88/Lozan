<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Brute-force protection: 5 attempts / minute / e-mail+IP and
    // 20 attempts / minute / IP (defined in AppServiceProvider).
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:admin-login')
        ->name('login.submit');
});

Route::middleware('auth:admin')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('/articles', ArticleController::class)->except(['show']);

    Route::post('/uploads/image', [UploadController::class, 'image'])
        ->name('uploads.image');
});

Route::redirect('/', '/dashboard');
