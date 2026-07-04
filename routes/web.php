<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Payment\SSLCommerzController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\WishlistController;
use Illuminate\Support\Facades\Route;

Route::controller(WebController::class)->group(function () {
    Route::get('/', 'root')->name('root');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'productDetails')->name('productDetails');
    Route::post('/get-color-by-size', 'getColorBySize')->name('getColorBySize');
    Route::get('/check-available-color', 'getAvailableColors')->name('getAvailableColors');
    Route::get('/search', 'search')->name('check.stock');
    Route::get('/get-signle-product-variant', 'getSignleProductVariantBySizeId')->name('getSignleProductVariantBySizeId');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactRequest')->name('contactRequest');
    Route::get('/about', 'about')->name('about');
    Route::get('/faq', 'faq')->name('faq');

    Route::get('/category/{slug}', 'categoryProducts')->name('categoryProducts'); // Category Products
    Route::get('/subcategory/{slug}', 'subcategoryProducts')->name('subcategoryProducts'); // SubCategory Products
    Route::get('/brand/{slug}', 'brandProducts')->name('brandProducts'); // Brand Products
    Route::get('/search-suggestions', 'searchSuggestions')->name('searchSuggestions'); // Search Suggestions

    // Dynamic Pages
    Route::get('/page/{slug}', 'page')->name('page');
    // Newsletter
    Route::post('/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('subscribe');
    // Order Tracking
    Route::get('/order-tracking', 'orderTracking')->name('orderTracking');
    Route::get('/order-tracking-result', 'orderTrackingDetails')->name('web.order-tracking-result');
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
    // User Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('user/dashboard', 'index')->name('user.dashboard');
        Route::get('user/orders', 'orders')->name('user.orders');
        Route::get('user/orders/{order}', 'orderDetails')->name('user.orderDetails');
        Route::get('user/order-products', 'orderProducts')->name('user.orderProducts');
        Route::get('user/profile', 'profile')->name('user.profile');
        Route::post('user/profile', 'updateProfile')->name('user.updateProfile');
        Route::get('user/address', 'address')->name('user.address');
        Route::post('user/address', 'updateAddress')->name('user.updateAddress');
        Route::get('/order/invoice-download/{id}', 'downloadInvoice')->name('order.invoice.download');
        Route::get('user/change-password', 'changePasswordForm')->name('user.changePasswordForm');
    });

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
        Route::get('/checkout', 'index')->name('checkout.index');
    });

    // Order Routes
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('web.orders');
        Route::post('/orders', 'store')->name('web.orders.store');
        Route::get('/orders/{order}', 'orderDetails')->name('web.orderDetails');
        Route::get('/order-invoice/{order}', 'orderInvoice')->name('web.orderInvoice');
        Route::get('/download-invoice/{order}', 'downloadInvoice')->name('web.downloadInvoice');
        Route::post('/cancel-order/{order}', 'cancelOrder')->name('web.cancelOrder');
    });

    // Wishlist Routes
    Route::controller(WishlistController::class)->group(function () {
        Route::get('/wishlist', 'index')->name('wishlist');
        Route::post('/add-to-wishlist', 'wishlistToggle')->name('wishlist.toggle');
        Route::get('/remove-from-wishlist/{id}', 'removeFromWishlist')->name('removeFromWishlist');
    });

    // Review Routes
    Route::controller(ReviewController::class)->group(function () {
        Route::post('/add-review', 'store')->name('user.addReview');
        Route::post('/reply-review', 'reply')->name('user.replyReview');
    });
});

// Stripe Routes
Route::controller(StripeController::class)->group(function () {
    Route::get('/payment/stripe/success', 'success')->name('stripe.success');
    Route::get('/payment/stripe/cancel', 'cancel')->name('stripe.cancel');
    Route::post('/payment/stripe/webhook', 'webhook')->name('stripe.webhook');
    Route::get('/payment/stripe/status/{orderNumber}', 'status')->name('stripe.status');
});

// SSLCommerz Routes
Route::controller(SSLCommerzController::class)->group(function () {
    Route::post('/payment/ssl/success', 'success')->name('ssl.success');
    Route::post('/payment/ssl/fail', 'fail')->name('ssl.fail');
    Route::post('/payment/ssl/cancel', 'cancel')->name('ssl.cancel');
    Route::post('/payment/ssl/ipn', 'ipn')->name('ssl.ipn');
});

@include 'admin.php';
