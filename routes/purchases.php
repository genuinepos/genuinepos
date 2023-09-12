<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Purchases\PurchaseOrderController;
use App\Http\Controllers\Purchases\PurchaseReturnController;
use App\Http\Controllers\Purchases\PurchaseProductController;
use App\Http\Controllers\Purchases\PurchaseSettingController;

Route::controller(PurchaseController::class)->prefix('purchases')->group(function () {

    Route::get('/', 'index')->name('purchases.index');
    Route::get('show/{id}', 'show')->name('purchases.show');
    Route::get('create', 'create')->name('purchases.create');
    Route::post('store', 'store')->name('purchases.store');
    Route::get('edit/{id}', 'edit')->name('purchases.edit');
    Route::get('search/by/invoice/id/{keyword}', 'searchPurchasesByInvoiceId')->name('purchases.search.by.invoice.id');

    Route::controller(PurchaseProductController::class)->prefix('products')->group(function () {

        Route::get('/', 'index')->name('purchases.products.index');
        Route::get('for/purchase/return/{purchase_id}', 'purchaseProductsForPurchaseReturn')->name('purchases.products.for.purchase.return');
    });

    Route::controller(PurchaseReturnController::class)->prefix('returns')->group(function () {

        Route::get('/', 'index')->name('purchase.returns.index');
        Route::get('show/{id}', 'show')->name('purchase.returns.show');
        Route::get('create', 'create')->name('purchase.returns.create');
        Route::post('store', 'store')->name('purchase.returns.store');
        Route::get('edit/{id}', 'edit')->name('purchase.returns.edit');
        Route::post('update/{id}', 'update')->name('purchase.returns.update');
        Route::delete('delete/{id}', 'delete')->name('purchase.returns.delete');
    });

    Route::controller(PurchaseSettingController::class)->prefix('settings')->group(function () {

        Route::get('/', 'index')->name('purchase.settings.index');
        Route::post('update', 'update')->name('purchase.settings.update');
    });

    Route::controller(PurchaseOrderController::class)->prefix('orders')->group(function () {
        Route::get('/', 'index')->name('purchase.orders.index');
        Route::get('create', 'create')->name('purchase.orders.create');
        Route::post('store', 'store')->name('purchase.orders.store');
        Route::get('show/{id}', 'show')->name('purchase.orders.show');
        Route::get('edit/{id}', 'edit')->name('purchase.orders.edit');
        Route::post('update/{id}', 'update')->name('purchase.orders.update');
    });
});

