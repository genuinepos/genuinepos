<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DraftController;
use App\Http\Controllers\Sales\AddSalesController;
use App\Http\Controllers\Sales\ShipmentController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SoldProductController;
use App\Http\Controllers\Sales\AddSaleSettingController;
use App\Http\Controllers\Sales\PosSaleSettingController;

Route::prefix('sales')->group(function () {

    Route::controller(AddSalesController::class)->prefix('add-sale')->group(function () {
        Route::get('/', 'index')->name('sales.index');
        Route::get('show/{id}', 'show')->name('sales.show');
        Route::get('create', 'create')->name('sales.create');
        Route::post('store', 'store')->name('sales.store');
        Route::get('edit/{id}', 'edit')->name('sales.edit');
        Route::post('update/{id}', 'update')->name('sales.update');
        Route::delete('delete/{id}', 'delete')->name('sales.delete');
        Route::get('print/challan/{id}', 'printChallan')->name('sales.print.challan');

        Route::controller(SoldProductController::class)->prefix('products')->group(function () {

            Route::get('/', 'index')->name('sale.products.index');
            Route::get('for/sales/return/{purchase_id}', 'soldProductsForSalesReturn')->name('sale.products.for.sales.return');
        });
    });

    Route::controller(SalesOrderController::class)->prefix('orders')->group(function () {

        Route::get('/', 'index')->name('sale.orders.index');
        Route::get('show/{id}', 'show')->name('sale.orders.show');
        Route::get('edit/{id}', 'edit')->name('sale.orders.edit');
        Route::post('update/{id}', 'update')->name('sale.orders.update');
        Route::delete('delete/{id}', 'delete')->name('sale.orders.delete');
    });

    Route::controller(QuotationController::class)->prefix('quotations')->group(function () {

        Route::get('/', 'index')->name('sale.quotations.index');
        Route::get('show/{id}', 'show')->name('sale.quotations.show');
        Route::get('edit/{id}', 'edit')->name('sale.quotations.edit');
        Route::post('update/{id}', 'update')->name('sale.quotations.update');
        Route::get('edit/status/{id}', 'editStatus')->name('sale.quotations.status.edit');
        Route::post('update/status/{id}', 'updateStatus')->name('sale.quotations.status.update');
        Route::delete('delete/{id}', 'delete')->name('sale.quotations.delete');
    });

    Route::controller(DraftController::class)->prefix('drafts')->group(function () {

        Route::get('/', 'index')->name('sale.drafts.index');
        Route::get('show/{id}', 'show')->name('sale.drafts.show');
        Route::get('edit/{id}', 'edit')->name('sale.drafts.edit');
        Route::post('update/{id}', 'update')->name('sale.drafts.edit');
        Route::delete('delete/{id}', 'delete')->name('sale.drafts.delete');
    });

    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {

        Route::get('/', 'index')->name('sale.shipments.index');
        Route::get('show/{id}', 'show')->name('sale.shipments.show');
        Route::get('edit/{id}', 'edit')->name('sale.shipments.edit');
        Route::post('update/{id}', 'update')->name('sale.shipments.update');
        Route::get('print/packing/slip/{id}', 'printPackingSlip')->name('sale.shipments.print.packing.slip');
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
