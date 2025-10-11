<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdsController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\BackofficeController;
use App\Http\Controllers\Auth\SocialController;

Route::redirect('/home', '/');

Route::get('/', [HomeController::class, 'index']) -> name('home');

Route::get('/register', [AuthController::class, 'registerForm']) -> name('register');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register.user');

Route::get('/produtos', [ProdsController::class, 'index'])->name('products.index');
Route::get('/produtos/{slug}', [ProdsController::class, 'show'])->name('products.details');
Route::middleware('auth')->group(function() {
    Route::post('/products/{product:slug}/reviews', [ProdsController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ProdsController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::middleware('web')->group(function () {
    Route::get('auth/google', [SocialController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
});

Route::middleware(['admin']) -> group(function() {
    Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backoffice.index');

    Route::get('/backoffice/users', [BackofficeController::class, 'users'])->name('backoffice.users');
    Route::post('/backoffice/users', [BackofficeController::class, 'storeUser'])->name('backoffice.users.store');
    Route::put('/backoffice/users/{id}', [BackofficeController::class, 'updateUser'])->name('backoffice.users.update');
    Route::delete('/backoffice/users/{id}', [BackofficeController::class, 'deleteUser'])->name('backoffice.users.delete');
});


Route::fallback([UtilsController::class, 'fallback']);
Route::get('/404', [UtilsController::class, 'fallback'])->name('fallback');

