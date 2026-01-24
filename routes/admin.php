<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductInventory;
use App\Http\Controllers\Admin\ProductInventoryController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('admin.dashboard');
    });

    Route::controller(MediaController::class)->group(function () {
        Route::get('/media', 'index')->name('admin.media');
        Route::get('/media/add', 'add')->name('admin.media.add');
        Route::post('/media', 'store')->name('admin.media.store');
        Route::get('/media//{id}/edit', 'edit')->name('admin.media.edit');
        Route::put('/media/{id}', 'update')->name('admin.media.update');
        Route::delete('/media/{id}', 'destroy')->name('admin.media.destroy');

        Route::get('/media/get-gallery-ajax', 'getGalleryAjax')->name('admin.media.getGalleryAjax');
        Route::post('/media/ajax-store', 'ajaxStore')->name('admin.media.ajaxStore');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index')->name('category.index');
        Route::post('/categories', 'store')->name('category.store');
        Route::get('/categories/{category}/edit', 'edit')->name('category.edit');
        Route::put('/categories/{category}', 'update')->name('category.update');
        Route::delete('/categories/{category}', 'destroy')->name('category.destroy');
    });

    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/sub-categories', 'index')->name('sub-category.index');
        Route::post('/sub-categories', 'store')->name('sub-category.store');
        Route::get('/sub-categories/{subCategory}/edit', 'edit')->name('sub-category.edit');
        Route::put('/sub-categories/{subCategory}', 'update')->name('sub-category.update');
        Route::delete('/sub-categories/{subCategory}', 'destroy')->name('sub-category.destroy');

        Route::get('/sub-categories/api', 'subCategoryApi')->name('getAllSubCategory');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brand.index');
        Route::post('/brands', 'store')->name('brand.store');
        Route::get('/brands/{brand}/edit', 'edit')->name('brand.edit');
        Route::put('/brands/{brand}', 'update')->name('brand.update');
        Route::delete('/brands/{brand}', 'destroy')->name('brand.destroy');
    });

    Route::controller(SizeController::class)->group(function () {
        Route::get('/sizes', 'index')->name('size.index');
        Route::post('/sizes', 'store')->name('size.store');
        Route::get('/sizes/{size}/edit', 'edit')->name('size.edit');
        Route::put('/sizes/{size}', 'update')->name('size.update');
        Route::delete('/sizes/{size}', 'destroy')->name('size.destroy');
    });

    Route::controller(ColorController::class)->group(function () {
        Route::get('/colors', 'index')->name('color.index');
        Route::post('/colors', 'store')->name('color.store');
        Route::get('/colors/{color}/edit', 'edit')->name('color.edit');
        Route::put('/colors/{color}', 'update')->name('color.update');
        Route::delete('/colors/{color}', 'destroy')->name('color.destroy');
    });

    Route::controller(TagController::class)->group(function () {
        Route::get('/tags', 'index')->name('tag.index');
        Route::post('/tags', 'store')->name('tag.store');
        Route::get('/tags/{tag}/edit', 'edit')->name('tag.edit');
        Route::put('/tags/{tag}', 'update')->name('tag.update');
        Route::delete('/tags/{tag}', 'destroy')->name('tag.destroy');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('product.index');
        Route::get('/products/add', 'add')->name('product.add');
        Route::get('/products/{product}/view', 'view')->name('product.view');
        Route::post('/products', 'store')->name('product.store');
        Route::get('/products/{product}/edit', 'edit')->name('product.edit');
        Route::put('/products/{product}', 'update')->name('product.update');
        Route::delete('/products/{product}', 'destroy')->name('product.destroy');
    });

    Route::controller(ProductInventoryController::class)->group(function () {
        Route::get('/products/{product}/inventories', 'index')->name('inventory.index');
        Route::post('/products/inventories', 'store')->name('inventory.store');
        Route::put('/products/inventories/{inventory}', 'update')->name('inventory.update');
        Route::delete('/products/inventories/{productInventory}', 'destroy')->name('inventory.destroy');
    });
});