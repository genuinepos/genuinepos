<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Purchases\PurchaseProductController;
use App\Http\Controllers\Purchases\PurchaseSettingController;

Route::controller(PurchaseController::class)->prefix('purchases')->group(function () {

    Route::get('/', 'index')->name('purchases.index');
    Route::get('show/{id}', 'show')->name('purchases.show');
    Route::get('create', 'create')->name('purchases.create');
    Route::post('store', 'store')->name('purchases.store');
    Route::get('edit/{id}', 'edit')->name('purchases.edit');
});

Route::controller(PurchaseProductController::class)->prefix('purchase-products')->group(function () {

    Route::get('/', 'index')->name('purchases.products.index');
});

Route::controller(PurchaseSettingController::class)->prefix('purchase-settings')->group(function () {

    Route::get('/', 'index')->name('purchase.settings.index');
    Route::post('update', 'update')->name('purchase.settings.update');
});
