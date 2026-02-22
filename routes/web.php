<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\WishlistController;
use Illuminate\Support\Facades\Route;

Route::controller(WebController::class)->group(function () {
    Route::get('/', 'root')->name('root');
    Route::get('/products', 'products')->name('products');
    Route::get('/product/{slug}', 'productDetails')->name('productDetails');
    Route::post('/get-color-by-size', 'getColorBySize')->name('getColorBySize');
    Route::get('/check-available-color', 'getAvailableColors')->name('getAvailableColors');
    Route::get('/search', 'search')->name('check.stock');
    Route::get('/get-signle-product-variant', 'getSignleProductVariantBySizeId')->name('getSignleProductVariantBySizeId');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/about', 'about')->name('about');
    Route::get('/category/{slug}', 'categoryProducts')->name('categoryProducts'); // Category Products
    Route::get('/subcategory/{slug}', 'subcategoryProducts')->name('subcategoryProducts'); // SubCategory Products
    Route::get('/brand/{slug}', 'brandProducts')->name('brandProducts'); // Brand Products
    Route::get('/search-suggestions', 'searchSuggestions')->name('searchSuggestions'); // Search Suggestions
});

Route::controller(AuthController::class)->group(function () {
    // Authentication Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginRequest')->name('loginRequest');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerRequest')->name('registerRequest');
    });
    // Logout Route
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'cart')->name('cart');
        Route::post('/add-to-cart', 'addToCart')->name('addToCart');
        Route::post('/update-cart', 'updateCart')->name('updateCart');
        Route::get('/delete-from-cart/{cart}', 'removeFromCart')->name('removeFromCart');
        Route::post('/coupon', 'applyCoupon')->name('applyCoupon');
    });

    // Checkout Routes
    Route::controller(CheckoutController::class)->group(function () {
        Route::post('/checkout', 'index')->name('checkout.index');
    });

    // Wishlist Routes
    Route::controller(WishlistController::class)->group(function () {
        Route::get('/wishlist', 'index')->name('wishlist');
        Route::post('/add-to-wishlist', 'wishlistToggle')->name('wishlist.toggle');
        Route::get('/remove-from-wishlist/{id}', 'removeFromWishlist')->name('removeFromWishlist');
    });
});

@include 'admin.php';