<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;

Route::controller(WebController::class)->group(function () {
    Route::get('/', 'root')->name('root');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'product')->name('product');
    Route::get('/cart', 'cart')->name('cart');
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