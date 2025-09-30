<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UtilsController;

Route::get('/', [HomeController::class, 'index']) -> name('home');

Route::fallback([UtilsController::class, 'fallback']);
