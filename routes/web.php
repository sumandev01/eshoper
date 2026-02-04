<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;

Route::controller(WebController::class)->group(function () {
    Route::get('/', 'root')->name('root');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'product')->name('product');
    Route::get('/check-available-color', 'getAvailableColors')->name('getAvailableColors');
    Route::get('/check-product-stock', 'checkStock')->name('checkStock');
    Route::get('/cart', 'cart')->name('cart');
    Route::POST('/add-to-cart', 'addToCart')->name('addToCart');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/about', 'about')->name('about');
});

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginRequest')->name('loginRequest');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerRequest')->name('registerRequest');
    });
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

@include 'admin.php';