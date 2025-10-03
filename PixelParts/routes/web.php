<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdsController;
use App\Http\Controllers\UtilsController;

Route::redirect('/home', '/');

Route::get('/', [HomeController::class, 'index']) -> name('home');

Route::get('/register', [AuthController::class, 'registerForm']) -> name('register');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register.user');

Route::get('/produtos', [ProdsController::class, 'index'])->name('products.index');
Route::get('/produtos/{slug}', [ProdsController::class, 'show'])->name('products.details');
Route::middleware('auth')->group(function() {
    Route::post('/products/{product:slug}/reviews', [ProdsController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ProdsController::class, 'destroy'])->name('reviews.destroy');
});


Route::fallback([UtilsController::class, 'fallback']);
