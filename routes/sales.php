<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DraftController;
use App\Http\Controllers\Sales\AddSalesController;
use App\Http\Controllers\Sales\DiscountController;
use App\Http\Controllers\Sales\ShipmentController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesReturnController;
use App\Http\Controllers\Sales\SoldProductController;
use App\Http\Controllers\Sales\AddSaleSettingController;
use App\Http\Controllers\Sales\PosSaleSettingController;
use App\Http\Controllers\Sales\Reports\SalesReportController;

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
        Route::get('search/by/{id}', 'searchByInvoiceId')->name('sales.search.by.invoice.id');

        Route::controller(SoldProductController::class)->prefix('products')->group(function () {

            Route::get('/', 'index')->name('sale.products.index');
            Route::get('for/sales/return/{sale_id}', 'soldProductsForSalesReturn')->name('sale.products.for.sales.return');
        });
    });

    Route::controller(SalesOrderController::class)->prefix('orders')->group(function () {

        Route::get('/', 'index')->name('sale.orders.index');
        Route::get('show/{id}', 'show')->name('sale.orders.show');
        Route::get('edit/{id}', 'edit')->name('sale.orders.edit');
        Route::post('update/{id}', 'update')->name('sale.orders.update');
    });

    Route::controller(QuotationController::class)->prefix('quotations')->group(function () {

        Route::get('/', 'index')->name('sale.quotations.index');
        Route::get('show/{id}', 'show')->name('sale.quotations.show');
        Route::get('edit/{id}', 'edit')->name('sale.quotations.edit');
        Route::post('update/{id}', 'update')->name('sale.quotations.update');
        Route::get('edit/status/{id}', 'editStatus')->name('sale.quotations.status.edit');
        Route::post('update/status/{id}', 'updateStatus')->name('sale.quotations.status.update');
    });

    Route::controller(DraftController::class)->prefix('drafts')->group(function () {

        Route::get('/', 'index')->name('sale.drafts.index');
        Route::get('show/{id}', 'show')->name('sale.drafts.show');
        Route::get('edit/{id}', 'edit')->name('sale.drafts.edit');
        Route::post('update/{id}', 'update')->name('sale.drafts.update');
    });

    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {

        Route::get('/', 'index')->name('sale.shipments.index');
        Route::get('show/{id}', 'show')->name('sale.shipments.show');
        Route::get('edit/{id}', 'edit')->name('sale.shipments.edit');
        Route::post('update/{id}', 'update')->name('sale.shipments.update');
        Route::get('print/packing/slip/{id}', 'printPackingSlip')->name('sale.shipments.print.packing.slip');
    });

    Route::controller(SalesReturnController::class)->prefix('returns')->group(function () {

        Route::get('/', 'index')->name('sales.returns.index');
        Route::get('show/{id}', 'show')->name('sales.returns.show');
        Route::get('create', 'create')->name('sales.returns.create');
        Route::post('store', 'store')->name('sales.returns.store');
        Route::get('edit/{id}', 'edit')->name('sales.returns.edit');
        Route::post('update/{id}', 'update')->name('sales.returns.update');
        Route::delete('delete/{id}', 'delete')->name('sales.returns.delete');
    });

    Route::controller(DiscountController::class)->prefix('discounts')->group(function () {

        Route::get('/', 'index')->name('sales.discounts.index');
        Route::get('create', 'create')->name('sales.discounts.create');
        Route::post('store', 'store')->name('sales.discounts.store');
        Route::get('edit/{id}', 'edit')->name('sales.discounts.edit');
        Route::post('update/{id}', 'update')->name('sales.discounts.update');
        Route::get('change/status/{id}', 'changeStatus')->name('sales.discounts.change.status');
        Route::delete('delete/{id}', 'delete')->name('sales.discounts.delete');
    });

    Route::controller(AddSaleSettingController::class)->prefix('add-sales-settings')->group(function () {

        Route::get('edit', 'edit')->name('add.sales.settings.edit');
        Route::post('update', 'update')->name('add.sales.settings.update');
    });

    Route::controller(PosSaleSettingController::class)->prefix('pos-sales-settings')->group(function () {

        Route::get('edit', 'edit')->name('pos.sales.settings.edit');
        Route::post('update', 'update')->name('pos.sales.settings.update');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(SalesReportController::class)->prefix('sales')->group(function () {

            Route::get('/', 'index')->name('reports.sales.report.index');
            Route::get('print', 'print')->name('reports.sales.report.print');
        });
    });
});
