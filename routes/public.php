<?php

use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/article/{slug}', ArticleController::class)
    ->name('articles.show')
    ->where('slug', '[A-Za-z0-9_-]+');
