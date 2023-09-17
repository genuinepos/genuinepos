<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Purchases\PurchaseOrderController;
use App\Http\Controllers\Purchases\PurchaseReturnController;
use App\Http\Controllers\Purchases\PurchaseProductController;
use App\Http\Controllers\Purchases\PurchaseSettingController;
use App\Http\Controllers\Purchases\Reports\PurchaseReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseOrderReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseProductReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseOrderProductReportController;

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
        Route::delete('delete/{id}', 'delete')->name('purchase.orders.delete');
        Route::get('print/supplier/copy/{id}', 'printSupplierCopy')->name('purchases.order.print.supplier.copy');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(PurchaseReportController::class)->prefix('purchases')->group(function () {
            Route::get('/', 'index')->name('reports.purchases.index');
            Route::get('print', 'print')->name('reports.purchases.print');
        });

        Route::controller(PurchaseProductReportController::class)->prefix('purchased-products')->group(function () {
            Route::get('/', 'index')->name('reports.purchased.products.index');
            Route::get('print', 'print')->name('reports.purchased.products.print');
        });

        Route::controller(PurchaseOrderReportController::class)->prefix('purchase-order')->group(function () {
            Route::get('/', 'index')->name('reports.purchase.orders.index');
            Route::get('print', 'print')->name('reports.purchase.orders.print');
        });

        Route::controller(PurchaseOrderProductReportController::class)->prefix('purchase-ordered-products')->group(function () {
            Route::get('/', 'index')->name('reports.purchase.ordered.products.index');
            Route::get('print', 'print')->name('reports.purchase.ordered.products.print');
        });

        Route::group(['prefix' => 'purchase/payments'], function () {
            Route::get('/', [PurchasePaymentReportController::class, 'index'])->name('reports.purchase.payments.index');
            Route::get('print', [PurchasePaymentReportController::class, 'print'])->name('reports.purchase.payments.print');
        });

        Route::group(['prefix' => 'sales/purchase'], function () {
            Route::get('/', [SalePurchaseReportController::class, 'index'])->name('reports.sales.purchases.index');
            Route::get('sale/purchase/amounts', [SalePurchaseReportController::class, 'salePurchaseAmounts'])->name('reports.profit.sales.purchases.amounts');
            Route::get('filter/sale/purchase/amounts', [SalePurchaseReportController::class, 'filterSalePurchaseAmounts'])->name('reports.profit.sales.filter.purchases.amounts');
            Route::get('print', [SalePurchaseReportController::class, 'printSalePurchase'])->name('reports.sales.purchases.print');
        });
    });
});

