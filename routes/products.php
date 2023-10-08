<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\UnitController;
use App\Http\Controllers\Products\BrandController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\WarrantyController;
use App\Http\Controllers\Products\PriceGroupController;
use App\Http\Controllers\Products\BulkVariantController;
use App\Http\Controllers\Products\SubCategoryController;
use App\Http\Controllers\Products\ProductSettingsController;
use App\Http\Controllers\Products\PriceGroupManageController;

Route::controller(ProductController::class)->prefix('products')->group(function () {

    Route::get('/', 'index')->name('products.index');
    Route::get('show/{id}', 'show')->name('products.show');
    Route::get('create', 'create')->name('products.create');
    Route::post('store', 'store')->name('products.store');
    Route::get('edit/{id}', 'edit')->name('products.edit');
    Route::post('update/{id}', 'update')->name('products.update');
    Route::delete('delete/{id}', 'delete')->name('products.delete');
    Route::get('form/part/{type}', 'formPart')->name('products.form.part');

    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::get('create', 'create')->name('categories.create');
        Route::post('store', 'store')->name('categories.store');
        Route::get('edit/{id}', 'edit')->name('categories.edit');
        Route::post('update/{id}', 'update')->name('categories.update');
        Route::delete('delete/{id}', 'delete')->name('categories.delete');
    });

    Route::controller(SubCategoryController::class)->prefix('subcategories')->group(function () {
        Route::get('/', 'index')->name('subcategories.index');
        Route::get('create/{fixedParentCategoryId?}', 'create')->name('subcategories.create');
        Route::post('store', 'store')->name('subcategories.store');
        Route::get('edit/{id}', 'edit')->name('subcategories.edit');
        Route::post('update/{id}', 'update')->name('subcategories.update');
        Route::delete('delete/{id}', 'delete')->name('subcategories.delete');
        Route::get('subcategories/by/categoryId/{categoryId}', 'subcategoriesByCategoryId')->name('subcategories.by.category.id');
    });

    Route::controller(UnitController::class)->prefix('units')->group(function () {
        Route::get('/', 'index')->name('units.index');
        Route::get('create/{isAllowedMultipleUnit?}', 'create')->name('units.create');
        Route::post('store', 'store')->name('units.store');
        Route::get('edit/{id}', 'edit')->name('units.edit');
        Route::post('update/{id}', 'update')->name('units.update');
        Route::delete('delete/{id}', 'delete')->name('units.delete');
    });

    Route::controller(BulkVariantController::class)->prefix('bulk-variants')->group(function () {

        Route::get('/', 'index')->name('product.bulk.variants.index');
        Route::get('create', 'create')->name('product.bulk.variants.create');
        Route::post('store', 'store')->name('product.bulk.variants.store');
        Route::get('edit/{id}', 'edit')->name('product.bulk.variants.edit');
        Route::post('update/{id}', 'update')->name('product.bulk.variants.update');
        Route::delete('delete/{id}', 'delete')->name('product.bulk.variants.delete');
    });

    Route::controller(BrandController::class)->prefix('brands')->group(function () {
        Route::get('/', 'index')->name('brands.index');
        Route::get('create', 'create')->name('brands.create');
        Route::post('store', 'store')->name('brands.store');
        Route::get('edit/{id}', 'edit')->name('brands.edit');
        Route::post('update/{id}', 'update')->name('brands.update');
        Route::delete('delete/{id}', 'delete')->name('brands.delete');
    });

    Route::controller(WarrantyController::class)->prefix('warranties')->group(function () {
        Route::get('/', 'index')->name('warranties.index');
        Route::get('create', 'create')->name('warranties.create');
        Route::post('store', 'store')->name('warranties.store');
        Route::get('edit/{id}', 'edit')->name('warranties.edit');
        Route::post('update/{id}', 'update')->name('warranties.update');
        Route::delete('delete/{id}', 'delete')->name('warranties.delete');
    });

    Route::controller(PriceGroupController::class)->prefix('selling-price-groups')->group(function () {
        Route::get('/', 'index')->name('selling.price.groups.index');
        Route::get('create', 'create')->name('selling.price.groups.create');
        Route::post('store', 'store')->name('selling.price.groups.store');
        Route::get('edit/{id}', 'edit')->name('selling.price.groups.edit');
        Route::post('update/{id}', 'update')->name('selling.price.groups.update');
        Route::delete('delete/{id}', 'delete')->name('selling.price.groups.delete');
        Route::get('change/status/{id}', 'changeStatus')->name('selling.price.groups.change.status');

        Route::controller(PriceGroupManageController::class)->prefix('manage')->group(function () {
            Route::get('index/{productId}/{type}', 'index')->name('selling.price.groups.manage.index');
            Route::post('store/or/update', 'storeOrUpdate')->name('selling.price.groups.manage.store.or.update');
        });
    });

    Route::controller(ProductSettingsController::class)->prefix('settings')->group(function () {
        Route::get('index', 'index')->name('product.settings.index');
        Route::post('update', 'update')->name('product.settings.update');
    });
});
