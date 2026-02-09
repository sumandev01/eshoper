<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;

Route::controller(WebController::class)->group(function () {
    Route::get('/', 'root')->name('root');
    Route::get('/products', 'products')->name('products');
    Route::get('/product/{slug}', 'productDetails')->name('productDetails');
    Route::POST('/get-color-by-size', 'getColorBySize')->name('getColorBySize');
    Route::get('/check-available-color', 'getAvailableColors')->name('getAvailableColors');

    Route::get('/search', 'search')->name('check.stock');



    Route::get('/get-signle-product-variant', 'getSignleProductVariantBySizeId')->name('getSignleProductVariantBySizeId');
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