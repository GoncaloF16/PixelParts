<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UtilsController;

Route::redirect('/home', '/');

Route::get('/', [HomeController::class, 'index']) -> name('home');

Route::get('/register', [AuthController::class, 'registerForm']) -> name('register');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register.user');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::fallback([UtilsController::class, 'fallback']);
