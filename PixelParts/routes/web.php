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
Route::get('/sobre', [HomeController::class, 'about']) -> name('about');
Route::get('/faq', [HomeController::class, 'faq']) -> name('faq');

Route::get('/register', [AuthController::class, 'registerForm']) -> name('register');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('register.user');

Route::get('/produtos', [ProdsController::class, 'index'])->name('products.index');
Route::get('/produtos/{slug}', [ProdsController::class, 'show'])->name('products.details');

// Cart recovery route (public, no auth required)
Route::get('/carrinho/recuperar/{token}', [CartController::class, 'recover'])->name('cart.recover');

Route::middleware('auth')->group(function() {
    Route::post('/products/{product:slug}/reviews', [ProdsController::class, 'storeReview'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ProdsController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout routes
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order-success', [CartController::class, 'orderSuccess'])->name('order.success');

    // Profile routes
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-billing', [HomeController::class, 'updateBilling'])->name('profile.update.billing');
    Route::put('/profile/update-shipping', [HomeController::class, 'updateShipping'])->name('profile.update.shipping');
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

    Route::get('/backoffice/stock', [BackofficeController::class, 'stock'])->name('backoffice.stock');
    Route::get('/backoffice/stock/excel', [BackofficeController::class, 'exportStockExcel'])->name('backoffice.stock.excel');
    Route::get('/backoffice/stock/{product}', [BackofficeController::class, 'getProduct'])->name('backoffice.stock.get');
    Route::post('/backoffice/stock', [BackofficeController::class, 'storeProduct'])->name('backoffice.stock.store');
    Route::put('/backoffice/stock/{produto}', [BackofficeController::class, 'updateProduct'])->name('backoffice.stock.update');
    Route::post('/backoffice/stock/bulk-delete', [BackofficeController::class, 'bulkDelete'])->name('backoffice.stock.bulk-delete');
    Route::delete('/backoffice/stock/{produto}', [BackofficeController::class, 'destroyProduct'])->name('backoffice.stock.delete');

    Route::get('/backoffice/orders', [BackofficeController::class, 'orders'])->name('backoffice.orders');
    Route::get('/backoffice/orders/{id}', [BackofficeController::class, 'getOrder'])->name('backoffice.orders.get');
    Route::put('/backoffice/orders/{id}', [BackofficeController::class, 'updateOrder'])->name('backoffice.orders.update');
    Route::delete('/backoffice/orders/{id}', [BackofficeController::class, 'deleteOrder'])->name('backoffice.orders.delete');

});


Route::fallback([UtilsController::class, 'fallback']);
Route::get('/404', [UtilsController::class, 'fallback'])->name('fallback');

