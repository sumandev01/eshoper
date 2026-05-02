<?php

use App\Enums\Permission\AdminAccessEnums;
use App\Enums\Permission\BrandPermission;
use App\Enums\Permission\CategoryPermission;
use App\Enums\Permission\ColorPermission;
use App\Enums\Permission\CouponPermission;
use App\Enums\Permission\MediaPermission;
use App\Enums\Permission\ProductInventoryPermission;
use App\Enums\Permission\ProductPermission;
use App\Enums\Permission\SizePermission;
use App\Enums\Permission\SliderPermission;
use App\Enums\Permission\SubCategoryPermission;
use App\Enums\Permission\TagPermission;
use App\Enums\Permission\UserPermission;
use App\Enums\Permission\UserRolePermission;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductInventory;
use App\Http\Controllers\Admin\ProductInventoryController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
    Route::get('/login', 'showLoginForm')->name('admin.login');
    Route::post('/login', 'login')->name('admin.login.submit');
    Route::get('/logout', 'logout')->name('admin.logout');
});
Route::middleware(['auth:web', 'can:' . AdminAccessEnums::AdminAccess->value])->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('admin.dashboard');
    });

    Route::controller(MediaController::class)->group(function () {
        Route::get('/media', 'index')->name('admin.media')->middleware('permission:' . MediaPermission::VIEW->value);
        Route::get('/media/add', 'add')->name('admin.media.add')->middleware('permission:' . MediaPermission::CREATE->value);
        Route::post('/media', 'store')->name('admin.media.store')->middleware('permission:' . MediaPermission::CREATE->value);
        Route::get('/media/{id}/edit', 'edit')->name('admin.media.edit')->middleware('permission:' . MediaPermission::UPDATE->value);
        Route::put('/media/{id}', 'update')->name('admin.media.update')->middleware('permission:' . MediaPermission::UPDATE->value);
        Route::delete('/media/{media}', 'destroy')->name('admin.media.destroy')->middleware('permission:' . MediaPermission::DELETE->value);
        Route::get('/media/get-gallery-ajax', 'getGalleryAjax')->name('admin.media.getGalleryAjax');
        Route::post('/media/ajax-store', 'ajaxStore')->name('admin.media.ajaxStore')->middleware('permission:' . MediaPermission::CREATE->value);
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('category.index')->middleware('permission:' . CategoryPermission::VIEW->value);
        Route::post('/categories', 'store')->name('category.store')->middleware('permission:' . CategoryPermission::CREATE->value);
        Route::get('/categories/{category}/edit', 'edit')->name('category.edit')->middleware('permission:' . CategoryPermission::UPDATE->value);
        Route::put('/categories/{category}', 'update')->name('category.update')->middleware('permission:' . CategoryPermission::UPDATE->value);
        Route::delete('/categories/{category}', 'destroy')->name('category.destroy')->middleware('permission:' . CategoryPermission::DELETE->value);
    });

    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/sub-categories', 'index')->name('sub-category.index')->middleware('permission:' . SubCategoryPermission::VIEW->value);
        Route::post('/sub-categories', 'store')->name('sub-category.store')->middleware('permission:' . SubCategoryPermission::CREATE->value);
        Route::get('/sub-categories/{subCategory}/edit', 'edit')->name('sub-category.edit')->middleware('permission:' . SubCategoryPermission::UPDATE->value);
        Route::put('/sub-categories/{subCategory}', 'update')->name('sub-category.update')->middleware('permission:' . SubCategoryPermission::UPDATE->value);
        Route::delete('/sub-categories/{subCategory}', 'destroy')->name('sub-category.destroy')->middleware('permission:' . SubCategoryPermission::DELETE->value);

        Route::get('/sub-categories/api', 'subCategoryApi')->name('getAllSubCategory');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brand.index')->middleware('permission:' . BrandPermission::VIEW->value);
        Route::post('/brands', 'store')->name('brand.store')->middleware('permission:' . BrandPermission::CREATE->value);
        Route::get('/brands/{brand}/edit', 'edit')->name('brand.edit')->middleware('permission:' . BrandPermission::UPDATE->value);
        Route::put('/brands/{brand}', 'update')->name('brand.update')->middleware('permission:' . BrandPermission::UPDATE->value);
        Route::delete('/brands/{brand}', 'destroy')->name('brand.destroy')->middleware('permission:' . BrandPermission::DELETE->value);
    });

    Route::controller(SizeController::class)->group(function () {
        Route::get('/sizes', 'index')->name('size.index')->middleware('permission:' . SizePermission::VIEW->value);
        Route::post('/sizes', 'store')->name('size.store')->middleware('permission:' . SizePermission::CREATE->value);
        Route::get('/sizes/{size}/edit', 'edit')->name('size.edit')->middleware('permission:' . SizePermission::UPDATE->value);
        Route::put('/sizes/{size}', 'update')->name('size.update')->middleware('permission:' . SizePermission::UPDATE->value);
        Route::delete('/sizes/{size}', 'destroy')->name('size.destroy')->middleware('permission:' . SizePermission::DELETE->value);
    });

    Route::controller(ColorController::class)->group(function () {
        Route::get('/colors', 'index')->name('color.index')->middleware('permission:' . ColorPermission::VIEW->value);
        Route::post('/colors', 'store')->name('color.store')->middleware('permission:' . ColorPermission::CREATE->value);
        Route::get('/colors/{color}/edit', 'edit')->name('color.edit')->middleware('permission:' . ColorPermission::UPDATE->value);
        Route::put('/colors/{color}', 'update')->name('color.update')->middleware('permission:' . ColorPermission::UPDATE->value);
        Route::delete('/colors/{color}', 'destroy')->name('color.destroy')->middleware('permission:' . ColorPermission::DELETE->value);
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('/tags', 'index')->name('tag.index')->middleware('permission:' . TagPermission::VIEW->value);
        Route::post('/tags', 'store')->name('tag.store')->middleware('permission:' . TagPermission::CREATE->value);
        Route::get('/tags/{tag}/edit', 'edit')->name('tag.edit')->middleware('permission:' . TagPermission::UPDATE->value);
        Route::put('/tags/{tag}', 'update')->name('tag.update')->middleware('permission:' . TagPermission::UPDATE->value);
        Route::delete('/tags/{tag}', 'destroy')->name('tag.destroy')->middleware('permission:' . TagPermission::DELETE->value);
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('product.index')->middleware('permission:' . ProductPermission::VIEW->value);
        Route::get('/products/add', 'add')->name('product.add')->middleware('permission:' . ProductPermission::CREATE->value);
        Route::get('/products/{product}/view', 'view')->name('product.view')->middleware('permission:' . ProductPermission::VIEW->value);
        Route::post('/products', 'store')->name('product.store')->middleware('permission:' . ProductPermission::CREATE->value);
        Route::get('/products/{product}/edit', 'edit')->name('product.edit')->middleware('permission:' . ProductPermission::UPDATE->value);
        Route::put('/products/{product}', 'update')->name('product.update')->middleware('permission:' . ProductPermission::UPDATE->value);
        Route::put('/products/{product}/trendy', 'updateTrendy')->name('product.update.trendy')->middleware('permission:' . ProductPermission::UPDATE->value);
        Route::delete('/products/{product}', 'destroy')->name('product.destroy')->middleware('permission:' . ProductPermission::DELETE->value);
    });

    Route::controller(ProductInventoryController::class)->group(function () {
        Route::get('/products/{product}/inventories', 'index')->name('inventory.index')->middleware('permission:' . ProductInventoryPermission::VIEW->value);
        Route::post('/products/inventories', 'store')->name('inventory.store')->middleware('permission:' . ProductInventoryPermission::CREATE->value);
        Route::put('/products/inventories/{inventory}', 'update')->name('inventory.update')->middleware('permission:' . ProductInventoryPermission::UPDATE->value);
        Route::delete('/products/inventories/{productInventory}', 'destroy')->name('inventory.destroy')->middleware('permission:' . ProductInventoryPermission::DELETE->value);
    });

    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupons', 'index')->name('coupon.index')->middleware('permission:' . CouponPermission::VIEW->value);
        Route::get('/coupons/add', 'add')->name('coupon.add')->middleware('permission:' . CouponPermission::CREATE->value);
        Route::post('/coupons', 'store')->name('coupon.store')->middleware('permission:' . CouponPermission::CREATE->value);
        Route::get('/coupons/{coupon}/edit', 'edit')->name('coupon.edit')->middleware('permission:' . CouponPermission::UPDATE->value);
        Route::put('/coupons/{coupon}', 'update')->name('coupon.update')->middleware('permission:' . CouponPermission::UPDATE->value);
        Route::delete('/coupons/{coupon}', 'destroy')->name('coupon.destroy')->middleware('permission:' . CouponPermission::DELETE->value);
    });

    Route::controller(SliderController::class)->group(function () {
        Route::get('/sliders', 'index')->name('slider.index')->middleware('permission:' . SliderPermission::VIEW->value);
        Route::get('/sliders/add', 'add')->name('slider.add')->middleware('permission:' . SliderPermission::CREATE->value);
        Route::post('/sliders', 'store')->name('slider.store')->middleware('permission:' . SliderPermission::CREATE->value);
        Route::get('/sliders/{slider}/edit', 'edit')->name('slider.edit')->middleware('permission:' . SliderPermission::UPDATE->value);
        Route::put('/sliders/{slider}', 'update')->name('slider.update')->middleware('permission:' . SliderPermission::UPDATE->value);
        Route::delete('/sliders/{slider}', 'destroy')->name('slider.destroy')->middleware('permission:' . SliderPermission::DELETE->value);
        Route::post('/sliders/reorder', 'reorder')->name('slider.reorder')->middleware('permission:' . SliderPermission::UPDATE->value);
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('admin.user.index')->middleware('permission:' . UserPermission::VIEW->value);
        Route::get('/users/add', 'add')->name('admin.user.add')->middleware('permission:' . UserPermission::CREATE->value);
        Route::post('/users', 'store')->name('admin.user.store')->middleware('permission:' . UserPermission::CREATE->value);
        Route::get('/users/{user}/view', 'view')->name('admin.user.view')->middleware('permission:' . UserPermission::VIEW->value);
        Route::get('/users/{user}/edit', 'edit')->name('admin.user.edit')->middleware('permission:' . UserPermission::UPDATE->value);
        Route::put('/users/{user}', 'update')->name('admin.user.update')->middleware('permission:' . UserPermission::UPDATE->value);
        Route::delete('/users/{user}', 'destroy')->name('admin.user.destroy')->middleware('permission:' . UserPermission::DELETE->value);
    });

    Route::controller(RolePermissionController::class)->group(function () {
        Route::get('/roles', 'index')->name('admin.role.index')->middleware('permission:' . UserRolePermission::VIEW->value);
        Route::get('/roles/add', 'add')->name('admin.role.add')->middleware('permission:' . UserRolePermission::CREATE->value);
        Route::post('/roles', 'store')->name('admin.role.store')->middleware('permission:' . UserRolePermission::CREATE->value);
        Route::get('/roles/{role}/view', 'view')->name('admin.role.view')->middleware('permission:' . UserRolePermission::VIEW->value);
        Route::get('/roles/{role}/edit', 'edit')->name('admin.role.edit')->middleware('permission:' . UserRolePermission::UPDATE->value);
        Route::put('/roles/{role}', 'update')->name('admin.role.update')->middleware('permission:' . UserRolePermission::UPDATE->value);
        Route::delete('/roles/{role}', 'destroy')->name('admin.role.destroy')->middleware('permission:' . UserRolePermission::DELETE->value);
    });
});