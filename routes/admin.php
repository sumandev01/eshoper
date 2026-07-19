<?php

use App\Enums\Permission\AboutPagePermission;
use App\Enums\Permission\AdminAccessEnums;
use App\Enums\Permission\BrandPermission;
use App\Enums\Permission\CategoryPermission;
use App\Enums\Permission\ColorPermission;
use App\Enums\Permission\CommentPermission;
use App\Enums\Permission\ContactMessagePermission;
use App\Enums\Permission\CouponPermission;
use App\Enums\Permission\FaqPermission;
use App\Enums\Permission\LocationPermission;
use App\Enums\Permission\MediaPermission;
use App\Enums\Permission\OrderPermission;

use App\Enums\Permission\ProductPermission;
use App\Enums\Permission\SettingPermission;
use App\Enums\Permission\SizePermission;
use App\Enums\Permission\SliderPermission;
use App\Enums\Permission\SubCategoryPermission;
use App\Enums\Permission\TagPermission;
use App\Enums\Permission\TeamMemberPermission;
use App\Enums\Permission\UserPermission;
use App\Enums\Permission\UserRolePermission;
use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
    Route::get('/login', 'showLoginForm')->name('admin.login');
    Route::post('/login', 'login')->name('admin.login.submit');
    Route::get('/logout', 'logout')->name('admin.logout');
});
Route::middleware(['is_admin', 'auth:web', 'can:'.AdminAccessEnums::AdminAccess->value])->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('admin.dashboard');
    });

    Route::controller(MediaController::class)->group(function () {
        Route::get('/media', 'index')->name('admin.media')->middleware('permission:'.MediaPermission::VIEW->value);
        Route::get('/media/add', 'add')->name('admin.media.add')->middleware('permission:'.MediaPermission::CREATE->value);
        Route::post('/media', 'store')->name('admin.media.store')->middleware('permission:'.MediaPermission::CREATE->value);
        Route::get('/media/{id}/edit', 'edit')->name('admin.media.edit')->middleware('permission:'.MediaPermission::UPDATE->value);
        Route::put('/media/{id}', 'update')->name('admin.media.update')->middleware('permission:'.MediaPermission::UPDATE->value);
        Route::delete('/media/{media}', 'destroy')->name('admin.media.destroy')->middleware('permission:'.MediaPermission::DELETE->value);
        Route::get('/media/get-gallery-ajax', 'getGalleryAjax')->name('admin.media.getGalleryAjax');
        Route::post('/media/ajax-store', 'ajaxStore')->name('admin.media.ajaxStore')->middleware('permission:'.MediaPermission::CREATE->value);
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('admin.category.index')->middleware('permission:'.CategoryPermission::VIEW->value);
        Route::post('/categories', 'store')->name('admin.category.store')->middleware('permission:'.CategoryPermission::CREATE->value);
        Route::get('/categories/{category}/edit', 'edit')->name('admin.category.edit')->middleware('permission:'.CategoryPermission::UPDATE->value);
        Route::put('/categories/{category}', 'update')->name('admin.category.update')->middleware('permission:'.CategoryPermission::UPDATE->value);
        Route::delete('/categories/{category}', 'destroy')->name('admin.category.destroy')->middleware('permission:'.CategoryPermission::DELETE->value);
    });

    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/sub-categories', 'index')->name('admin.sub-category.index')->middleware('permission:'.SubCategoryPermission::VIEW->value);
        Route::post('/sub-categories', 'store')->name('admin.sub-category.store')->middleware('permission:'.SubCategoryPermission::CREATE->value);
        Route::get('/sub-categories/{subCategory}/edit', 'edit')->name('admin.sub-category.edit')->middleware('permission:'.SubCategoryPermission::UPDATE->value);
        Route::put('/sub-categories/{subCategory}', 'update')->name('admin.sub-category.update')->middleware('permission:'.SubCategoryPermission::UPDATE->value);
        Route::delete('/sub-categories/{subCategory}', 'destroy')->name('admin.sub-category.destroy')->middleware('permission:'.SubCategoryPermission::DELETE->value);

        Route::get('/sub-categories/api', 'subCategoryApi')->name('admin.getAllSubCategory');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('admin.brand.index')->middleware('permission:'.BrandPermission::VIEW->value);
        Route::post('/brands', 'store')->name('admin.brand.store')->middleware('permission:'.BrandPermission::CREATE->value);
        Route::get('/brands/{brand}/edit', 'edit')->name('admin.brand.edit')->middleware('permission:'.BrandPermission::UPDATE->value);
        Route::put('/brands/{brand}', 'update')->name('admin.brand.update')->middleware('permission:'.BrandPermission::UPDATE->value);
        Route::delete('/brands/{brand}', 'destroy')->name('admin.brand.destroy')->middleware('permission:'.BrandPermission::DELETE->value);
    });

    Route::controller(SizeController::class)->group(function () {
        Route::get('/sizes', 'index')->name('admin.size.index')->middleware('permission:'.SizePermission::VIEW->value);
        Route::post('/sizes', 'store')->name('admin.size.store')->middleware('permission:'.SizePermission::CREATE->value);
        Route::get('/sizes/{size}/edit', 'edit')->name('admin.size.edit')->middleware('permission:'.SizePermission::UPDATE->value);
        Route::put('/sizes/{size}', 'update')->name('admin.size.update')->middleware('permission:'.SizePermission::UPDATE->value);
        Route::delete('/sizes/{size}', 'destroy')->name('admin.size.destroy')->middleware('permission:'.SizePermission::DELETE->value);
    });

    Route::controller(ColorController::class)->group(function () {
        Route::get('/colors', 'index')->name('admin.color.index')->middleware('permission:'.ColorPermission::VIEW->value);
        Route::post('/colors', 'store')->name('admin.color.store')->middleware('permission:'.ColorPermission::CREATE->value);
        Route::get('/colors/{color}/edit', 'edit')->name('admin.color.edit')->middleware('permission:'.ColorPermission::UPDATE->value);
        Route::put('/colors/{color}', 'update')->name('admin.color.update')->middleware('permission:'.ColorPermission::UPDATE->value);
        Route::delete('/colors/{color}', 'destroy')->name('admin.color.destroy')->middleware('permission:'.ColorPermission::DELETE->value);
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('/tags', 'index')->name('admin.tag.index')->middleware('permission:'.TagPermission::VIEW->value);
        Route::post('/tags', 'store')->name('admin.tag.store')->middleware('permission:'.TagPermission::CREATE->value);
        Route::get('/tags/{tag}/edit', 'edit')->name('admin.tag.edit')->middleware('permission:'.TagPermission::UPDATE->value);
        Route::put('/tags/{tag}', 'update')->name('admin.tag.update')->middleware('permission:'.TagPermission::UPDATE->value);
        Route::delete('/tags/{tag}', 'destroy')->name('admin.tag.destroy')->middleware('permission:'.TagPermission::DELETE->value);
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('admin.product.index')->middleware('permission:'.ProductPermission::VIEW->value);
        Route::get('/products/add', 'add')->name('admin.product.add')->middleware('permission:'.ProductPermission::CREATE->value);
        Route::get('/products/{product}/view', 'view')->name('admin.product.view')->middleware('permission:'.ProductPermission::VIEW->value);
        Route::post('/products', 'store')->name('admin.product.store')->middleware('permission:'.ProductPermission::CREATE->value);
        Route::get('/products/{product}/edit', 'edit')->name('admin.product.edit')->middleware('permission:'.ProductPermission::UPDATE->value);
        Route::put('/products/{product}', 'update')->name('admin.product.update')->middleware('permission:'.ProductPermission::UPDATE->value);
        Route::put('/products/{product}/trendy', 'updateTrendy')->name('admin.product.update.trendy')->middleware('permission:'.ProductPermission::UPDATE->value);
        Route::delete('/products/{product}', 'destroy')->name('admin.product.destroy')->middleware('permission:'.ProductPermission::DELETE->value);
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('admin.order.index')->middleware('permission:'.OrderPermission::VIEW->value);
        Route::get('/orders/{order}/view', 'view')->name('admin.order.view')->middleware('permission:'.OrderPermission::VIEW->value);
        Route::get('/orders/{order}/edit', 'edit')->name('admin.order.edit')->middleware('permission:'.OrderPermission::UPDATE->value);
        Route::put('/orders/{order}', 'update')->name('admin.order.update')->middleware('permission:'.OrderPermission::UPDATE->value);
        Route::delete('/orders/{order}', 'destroy')->name('admin.order.destroy')->middleware('permission:'.OrderPermission::DELETE->value);
    });

    Route::controller(\App\Http\Controllers\Admin\CourierController::class)->group(function () {
        Route::get('/couriers', 'index')->name('admin.couriers.index');
        Route::get('/couriers/create', 'create')->name('admin.couriers.create');
        Route::post('/couriers', 'store')->name('admin.couriers.store');
        Route::get('/couriers/{courier}/edit', 'edit')->name('admin.couriers.edit');
        Route::put('/couriers/{courier}', 'update')->name('admin.couriers.update');
        Route::delete('/couriers/{courier}', 'destroy')->name('admin.couriers.destroy');
    });

    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupons', 'index')->name('admin.coupon.index')->middleware('permission:'.CouponPermission::VIEW->value);
        Route::get('/coupons/add', 'add')->name('admin.coupon.add')->middleware('permission:'.CouponPermission::CREATE->value);
        Route::post('/coupons', 'store')->name('admin.coupon.store')->middleware('permission:'.CouponPermission::CREATE->value);
        Route::get('/coupons/{coupon}/edit', 'edit')->name('admin.coupon.edit')->middleware('permission:'.CouponPermission::UPDATE->value);
        Route::put('/coupons/{coupon}', 'update')->name('admin.coupon.update')->middleware('permission:'.CouponPermission::UPDATE->value);
        Route::delete('/coupons/{coupon}', 'destroy')->name('admin.coupon.destroy')->middleware('permission:'.CouponPermission::DELETE->value);
    });

    Route::controller(SliderController::class)->group(function () {
        Route::get('/sliders', 'index')->name('admin.slider.index')->middleware('permission:'.SliderPermission::VIEW->value);
        Route::get('/sliders/add', 'add')->name('admin.slider.add')->middleware('permission:'.SliderPermission::CREATE->value);
        Route::post('/sliders', 'store')->name('admin.slider.store')->middleware('permission:'.SliderPermission::CREATE->value);
        Route::get('/sliders/{slider}/edit', 'edit')->name('admin.slider.edit')->middleware('permission:'.SliderPermission::UPDATE->value);
        Route::put('/sliders/{slider}', 'update')->name('admin.slider.update')->middleware('permission:'.SliderPermission::UPDATE->value);
        Route::delete('/sliders/{slider}', 'destroy')->name('admin.slider.destroy')->middleware('permission:'.SliderPermission::DELETE->value);
        Route::post('/sliders/reorder', 'reorder')->name('admin.slider.reorder')->middleware('permission:'.SliderPermission::UPDATE->value);
    });

    Route::controller(\App\Http\Controllers\Admin\ProductReviewController::class)->group(function () {
        Route::get('/product-reviews', 'index')->name('admin.product-review.index')->middleware('permission:'.CommentPermission::VIEW->value);
        Route::put('/product-reviews/{comment}/update', 'update')->name('admin.product-review.update')->middleware('permission:'.CommentPermission::UPDATE->value);
        Route::delete('/product-reviews/{comment}', 'destroy')->name('admin.product-review.destroy')->middleware('permission:'.CommentPermission::DELETE->value);
    });

    Route::controller(ContactMessageController::class)->group(function () {
        Route::get('/contact-messages', 'index')->name('admin.contact-message.index')->middleware('permission:'.ContactMessagePermission::VIEW->value);

        // Ajax
        Route::get('/contact-messages/{contactMessage}/view', 'view')->name('admin.contact-message.view')->middleware('permission:'.ContactMessagePermission::VIEW->value);
    });

    Route::controller(AboutPageController::class)->group(function () {
        Route::get('/about-page', 'index')->name('admin.about-page.index')->middleware('permission:'.AboutPagePermission::VIEW->value);
        Route::put('/about-page', 'update')->name('admin.about-page.update')->middleware('permission:'.AboutPagePermission::UPDATE->value);
    });

    Route::controller(FaqController::class)->group(function () {
        Route::get('/faq', 'index')->name('admin.faq.index')->middleware('permission:'.FaqPermission::VIEW->value);
        Route::post('/faq', 'store')->name('admin.faq.store')->middleware('permission:'.FaqPermission::CREATE->value);
        Route::get('/faq/{faq}/edit', 'edit')->name('admin.faq.edit')->middleware('permission:'.FaqPermission::UPDATE->value);
        Route::put('/faq/{faq}', 'update')->name('admin.faq.update')->middleware('permission:'.FaqPermission::UPDATE->value);
        Route::post('/faq/reorder', 'reorder')->name('admin.faq.reorder')->middleware('permission:'.FaqPermission::UPDATE->value);
        Route::delete('/faq/{faq}', 'destroy')->name('admin.faq.destroy')->middleware('permission:'.FaqPermission::DELETE->value);
    });

    Route::controller(TeamMemberController::class)->group(function () {
        Route::get('/team-members', 'index')->name('admin.team-member.index')->middleware('permission:'.TeamMemberPermission::VIEW->value);
        Route::get('/team-members/add', 'add')->name('admin.team-member.add')->middleware('permission:'.TeamMemberPermission::CREATE->value);
        Route::post('/team-members', 'store')->name('admin.team-member.store')->middleware('permission:'.TeamMemberPermission::CREATE->value);
        Route::get('/team-members/{teamMember}/edit', 'edit')->name('admin.team-member.edit')->middleware('permission:'.TeamMemberPermission::UPDATE->value);
        Route::put('/team-members/{teamMember}', 'update')->name('admin.team-member.update')->middleware('permission:'.TeamMemberPermission::UPDATE->value);
        Route::delete('/team-members/{teamMember}', 'destroy')->name('admin.team-member.destroy')->middleware('permission:'.TeamMemberPermission::DELETE->value);
        // Reorder team members
        Route::post('/team-members/reorder', 'reorder')->name('admin.team-member.reorder')->middleware('permission:'.TeamMemberPermission::UPDATE->value);
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('admin.user.index')->middleware('permission:'.UserPermission::VIEW->value);
        Route::get('/users/add', 'add')->name('admin.user.add')->middleware('permission:'.UserPermission::CREATE->value);
        Route::post('/users', 'store')->name('admin.user.store')->middleware('permission:'.UserPermission::CREATE->value);
        Route::get('/users/{user}/view', 'view')->name('admin.user.view')->middleware('permission:'.UserPermission::VIEW->value);
        Route::get('/users/{user}/edit', 'edit')->name('admin.user.edit')->middleware('permission:'.UserPermission::UPDATE->value);
        Route::put('/users/{user}', 'update')->name('admin.user.update')->middleware('permission:'.UserPermission::UPDATE->value);
        Route::delete('/users/{user}', 'destroy')->name('admin.user.destroy')->middleware('permission:'.UserPermission::DELETE->value);
        Route::post('/users/toggle-status/{id}', 'toggleStatus')->name('admin.user.toggleStatus')->middleware('permission:'.UserPermission::UPDATE->value);
    });

    Route::controller(RolePermissionController::class)->group(function () {
        Route::get('/roles', 'index')->name('admin.role.index')->middleware('permission:'.UserRolePermission::VIEW->value);
        Route::get('/roles/add', 'add')->name('admin.role.add')->middleware('permission:'.UserRolePermission::CREATE->value);
        Route::post('/roles', 'store')->name('admin.role.store')->middleware('permission:'.UserRolePermission::CREATE->value);
        Route::get('/roles/{role}/view', 'view')->name('admin.role.view')->middleware('permission:'.UserRolePermission::VIEW->value);
        Route::get('/roles/{role}/edit', 'edit')->name('admin.role.edit')->middleware('permission:'.UserRolePermission::UPDATE->value);
        Route::put('/roles/{role}', 'update')->name('admin.role.update')->middleware('permission:'.UserRolePermission::UPDATE->value);
        Route::delete('/roles/{role}', 'destroy')->name('admin.role.destroy')->middleware('permission:'.UserRolePermission::DELETE->value);
    });

    Route::controller(LocationController::class)->group(function () {
        Route::get('/locations', 'index')->name('admin.location.index')->middleware('permission:'.LocationPermission::VIEW->value);
        Route::get('/locations/create', 'create')->name('admin.location.create')->middleware('permission:'.LocationPermission::CREATE->value);
        
        Route::post('/locations/country', 'storeCountry')->name('admin.location.country.store')->middleware('permission:'.LocationPermission::CREATE->value);
        Route::post('/locations/state', 'storeState')->name('admin.location.state.store')->middleware('permission:'.LocationPermission::CREATE->value);
        Route::post('/locations/states/sync', 'syncStatesOnline')->name('admin.location.states.sync')->middleware('permission:'.LocationPermission::CREATE->value);

        Route::delete('/locations/country/{id}', 'destroyCountry')->name('admin.location.country.destroy')->middleware('permission:'.LocationPermission::DELETE->value);
        Route::delete('/locations/state/{id}', 'destroyState')->name('admin.location.state.destroy')->middleware('permission:'.LocationPermission::DELETE->value);
    });

    Route::controller(\App\Http\Controllers\Admin\ShippingCostController::class)->group(function () {
        Route::get('/shipping-costs', 'index')->name('admin.shipping_cost.index');
        Route::get('/shipping-costs/create', 'create')->name('admin.shipping_cost.create');
        Route::post('/shipping-costs', 'store')->name('admin.shipping_cost.store');
        Route::get('/shipping-costs/{shippingCost}/edit', 'edit')->name('admin.shipping_cost.edit');
        Route::put('/shipping-costs/{shippingCost}', 'update')->name('admin.shipping_cost.update');
        Route::delete('/shipping-costs/{shippingCost}', 'destroy')->name('admin.shipping_cost.destroy');
    });

    Route::controller(BannerController::class)->group(function () {
        Route::get('/banner-offers', 'index')->name('admin.settings.offers');
        Route::post('/banner-offers', 'update')->name('admin.settings.offers.update');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('admin.settings.index')->middleware('permission:'.SettingPermission::SettingAccess->value);
        Route::put('/settings', 'update')->name('admin.settings.update')->middleware('permission:'.SettingPermission::SettingAccess->value);

        Route::get('/settings/payment-gateways', 'paymentGateways')->name('admin.settings.payment-gateways');
        Route::post('/settings/payment-gateways', 'updatePaymentGateways')->name('admin.settings.payment-gateways.update');

        Route::get('/settings/theme-layouts', 'themeLayouts')->name('admin.settings.theme-layouts');
        Route::post('/settings/theme-layouts', 'updateThemeLayouts')->name('admin.settings.theme-layouts.update');
    });

    // Blog routes
    Route::controller(\App\Http\Controllers\Admin\BlogCategoryController::class)->group(function () {
        Route::get('/blog-categories', 'index')->name('admin.blog_categories.index');
        Route::post('/blog-categories', 'store')->name('admin.blog_categories.store');
        Route::put('/blog-categories/{category}', 'update')->name('admin.blog_categories.update');
        Route::delete('/blog-categories/{category}', 'destroy')->name('admin.blog_categories.destroy');
    });

    Route::controller(\App\Http\Controllers\Admin\BlogController::class)->group(function () {
        Route::get('/blogs', 'index')->name('admin.blogs.index');
        Route::get('/blogs/create', 'create')->name('admin.blogs.create');
        Route::post('/blogs', 'store')->name('admin.blogs.store');
        Route::get('/blogs/{blog}/edit', 'edit')->name('admin.blogs.edit');
        Route::put('/blogs/{blog}', 'update')->name('admin.blogs.update');
        Route::delete('/blogs/{blog}', 'destroy')->name('admin.blogs.destroy');
    });

    // Blog Comments routes
    Route::controller(\App\Http\Controllers\Admin\BlogCommentController::class)->group(function () {
        Route::get('/blog-comments', 'index')->name('admin.blog-comments.index');
        Route::post('/blog-comments/settings', 'updateSettings')->name('admin.blog-comments.update-settings');
        Route::post('/blog-comments/{id}/status', 'status')->name('admin.blog-comments.status');
        Route::delete('/blog-comments/{id}', 'destroy')->name('admin.blog-comments.destroy');
    });

    // Pages routes
    Route::controller(\App\Http\Controllers\Admin\PageController::class)->group(function () {
        Route::get('/pages', 'index')->name('admin.pages.index');
        Route::get('/pages/create', 'create')->name('admin.pages.create');
        Route::post('/pages', 'store')->name('admin.pages.store');
        Route::get('/pages/{page}/edit', 'edit')->name('admin.pages.edit');
        Route::put('/pages/{page}', 'update')->name('admin.pages.update');
        Route::delete('/pages/{page}', 'destroy')->name('admin.pages.destroy');
    });

    // Menus routes
    Route::controller(\App\Http\Controllers\Admin\MenuController::class)->group(function () {
        Route::get('/menus', 'index')->name('admin.menus.index');
        Route::post('/menus', 'store')->name('admin.menus.store');
        Route::get('/menus/{menu}/builder', 'builder')->name('admin.menus.builder');
        Route::post('/menus/{menu}/items', 'storeItem')->name('admin.menus.items.store');
        Route::put('/menus/items/{menuItem}', 'updateItem')->name('admin.menus.items.update');
        Route::delete('/menus/items/{menuItem}', 'destroyItem')->name('admin.menus.items.destroy');
    });

    // Newsletter Routes
    Route::get('/newsletters', [App\Http\Controllers\NewsletterController::class, 'adminIndex'])->name('admin.newsletter.index');
    Route::delete('/newsletters/{newsletter}', [App\Http\Controllers\NewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');

    // Payment Methods Routes
    Route::controller(\App\Http\Controllers\Admin\PaymentMethodController::class)->group(function () {
        Route::get('/payment-methods', 'index')->name('admin.payment-methods.index');
        Route::post('/payment-methods', 'store')->name('admin.payment-methods.store');
        Route::get('/payment-methods/{paymentMethod}/edit', 'edit')->name('admin.payment-methods.edit');
        Route::put('/payment-methods/{paymentMethod}', 'update')->name('admin.payment-methods.update');
        Route::delete('/payment-methods/{paymentMethod}', 'destroy')->name('admin.payment-methods.destroy');
        Route::post('/payment-methods/reorder', 'reorder')->name('admin.payment-methods.reorder');
    });

    // Store Features Routes
    Route::controller(\App\Http\Controllers\Admin\StoreFeatureController::class)->group(function () {
        Route::get('/store-features', 'index')->name('admin.store-features.index');
        Route::post('/store-features', 'store')->name('admin.store-features.store');
        Route::get('/store-features/{storeFeature}/edit', 'edit')->name('admin.store-features.edit');
        Route::put('/store-features/{storeFeature}', 'update')->name('admin.store-features.update');
        Route::delete('/store-features/{storeFeature}', 'destroy')->name('admin.store-features.destroy');
        Route::post('/store-features/reorder', 'reorder')->name('admin.store-features.reorder');
    });
});


