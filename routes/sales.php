<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\AddSalesController;
use App\Http\Controllers\Sales\AddSaleSettingController;
use App\Http\Controllers\Sales\PosSaleSettingController;

Route::prefix('sales')->group(function () {

    Route::controller(AddSalesController::class)->prefix('add-sale')->group(function () {
        Route::get('/', 'index')->name('sales.index');
        Route::get('create', 'create')->name('sales.create');
        Route::post('store', 'store')->name('sales.store');
        Route::get('edit/{id}', 'edit')->name('sales.edit');
        Route::post('update/{id}', 'update')->name('sales.update');
        Route::delete('delete/{id}', 'delete')->name('sales.delete');
    });

    Route::controller(AddSaleSettingController::class)->prefix('add-sales-settings')->group(function () {

        Route::get('edit', 'edit')->name('add.sales.settings.edit');
        Route::post('update', 'update')->name('add.sales.settings.update');
    });

    Route::controller(PosSaleSettingController::class)->prefix('pos-sales-settings')->group(function () {

        Route::get('edit', 'edit')->name('pos.sales.settings.edit');
        Route::post('update', 'update')->name('pos.sales.settings.update');
    });
});
