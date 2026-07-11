<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Payment\SSLCommerzController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PdfController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\OrderTrackingController;
use App\Http\Controllers\Web\WishlistController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'root')->name('root');
});

Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/product/{slug}', 'productDetails')->name('product.details');
    Route::post('/get-color-by-size', 'getColorBySize')->name('product.color.by.size');
    Route::get('/check-available-color', 'getAvailableColors')->name('product.available.colors');
    Route::get('/search', 'search')->name('check.stock');
    Route::get('/get-signle-product-variant', 'getSignleProductVariantBySizeId')->name('product.variant.single');
    Route::get('/category/{slug}', 'categoryProducts')->name('category.products');
    Route::get('/subcategory/{slug}', 'subcategoryProducts')->name('subcategory.products');
    Route::get('/brand/{slug}', 'brandProducts')->name('brand.products');
    Route::get('/search-suggestions', 'searchSuggestions')->name('search.suggestions');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactRequest')->name('contact.submit');
    Route::get('/about', 'about')->name('about');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/page/{slug}', 'page')->name('page');
});

Route::controller(OrderTrackingController::class)->group(function () {
    Route::get('/order-tracking', 'orderTracking')->name('orderTracking');
    Route::get('/order-tracking-result', 'orderTrackingDetails')->name('order.tracking.result');
});

// Blog Routes
Route::controller(BlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('web.blogs.index');
    Route::get('/blog/{slug}', 'show')->name('web.blogs.show');
    Route::post('/blog/{id}/comment', 'storeComment')->name('web.blogs.comment');
});

// Newsletter
Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');

// AJAX Location Route
Route::get('/locations/states/{country_id}', [LocationController::class, 'getStatesByCountry'])->name('web.location.states');

Route::controller(AuthController::class)->group(function () {
    // Authentication Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.login')->whereIn('provider', ['google', 'facebook', 'github']);
        Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social.callback')->whereIn('provider', ['google', 'facebook', 'github']);
        Route::post('/login', 'loginRequest')->name('login.submit');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'registerRequest')->name('register.submit');
    });
    // Logout Route
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('user/dashboard', 'index')->name('user.dashboard');
        Route::get('user/orders', 'orders')->name('user.orders');
        Route::get('user/orders/{order}', 'orderDetails')->name('user.order.details');
        Route::get('user/order-products', 'orderProducts')->name('user.order.products');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('user/profile', 'profile')->name('user.profile');
        Route::post('user/profile', 'updateProfile')->name('user.profile.update');
        Route::get('user/change-password', 'changePasswordForm')->name('user.password.change');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::get('user/address', 'address')->name('user.address');
        Route::post('user/address', 'updateAddress')->name('user.address.update');
    });

    Route::controller(PdfController::class)->group(function () {
        Route::get('/order/invoice-download/{id}', 'downloadInvoice')->name('order.invoice.download');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'cart')->name('cart');
        Route::post('/add-to-cart', 'addToCart')->name('cart.add');
        Route::post('/update-cart', 'updateCart')->name('cart.update');
        Route::get('/delete-from-cart/{cart}', 'removeFromCart')->name('cart.remove');
        Route::post('/coupon', 'applyCoupon')->name('cart.coupon.apply');
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
        Route::get('/order-invoice/{order}', 'orderInvoice')->name('user.order.invoice');
        Route::get('/download-invoice/{order}', 'downloadInvoice')->name('user.invoice.download');
        Route::post('/cancel-order/{order}', 'cancelOrder')->name('user.order.cancel');
    });

    // Wishlist Routes
    Route::controller(WishlistController::class)->group(function () {
        Route::get('/wishlist', 'index')->name('wishlist');
        Route::post('/add-to-wishlist', 'wishlistToggle')->name('wishlist.toggle');
        Route::get('/remove-from-wishlist/{id}', 'removeFromWishlist')->name('wishlist.remove');
    });

    // Review Routes
    Route::controller(ReviewController::class)->group(function () {
        Route::post('/add-review', 'store')->name('review.store');
        Route::post('/reply-review', 'reply')->name('review.reply');
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

/**
 * ==============================================================================
 * AJAX Web Cron (Poor Man's Cron)
 * ==============================================================================
 * This route acts as a background task runner for environments where traditional 
 * SSH/Terminal cron jobs are unavailable (e.g., Shared Hosting).
 * 
 * How it works:
 * - It is triggered asynchronously via an AJAX (fetch) call from the main layout 
 *   (app.blade.php) every time a user loads a page.
 * - Cache rate-limiting (1 minute) is applied to prevent performance degradation 
 *   and stop the Artisan command from executing on every single page load.
 * 
 * Purpose: 
 * Primarily used to automatically cancel unpaid Stripe/SSLCommerz orders 
 * by executing the `orders:cancel-unpaid` command in the background.
 * ==============================================================================
 */
Route::get('/run-background-tasks', function () {
    $lastRun = \Illuminate\Support\Facades\Cache::get('last_cron_run');
    if (!$lastRun || now()->diffInMinutes($lastRun) >= 1) {
        \Illuminate\Support\Facades\Artisan::call('orders:cancel-unpaid');
        \Illuminate\Support\Facades\Cache::put('last_cron_run', now(), now()->addMinutes(1));
        return response()->json(['status' => 'executed']);
    }
    return response()->json(['status' => 'skipped']);
})->name('background.tasks');


require __DIR__.'/admin.php';