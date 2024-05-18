<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Purchases\PurchaseOrderController;
use App\Http\Controllers\Purchases\PurchaseReturnController;
use App\Http\Controllers\Purchases\PurchaseProductController;
use App\Http\Controllers\Purchases\PurchaseOrderToInvoiceController;
use App\Http\Controllers\Purchases\Reports\PurchaseReportController;
use App\Http\Controllers\Purchases\Reports\SalePurchaseReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseOrderReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseReturnReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseProductReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseOrderProductReportController;
use App\Http\Controllers\Purchases\Reports\PurchaseReturnProductReportController;
use App\Http\Controllers\Purchases\Reports\PaymentAgainstPurchaseReportController;

Route::controller(PurchaseController::class)->prefix('purchases')->group(function () {

    Route::get('index/{supplierAccountId?}', 'index')->name('purchases.index');
    Route::get('show/{id}', 'show')->name('purchases.show');
    Route::get('print/{id}', 'print')->name('purchases.print');
    Route::get('create', 'create')->name('purchases.create');
    Route::post('store', 'store')->name('purchases.store');
    Route::get('edit/{id}', 'edit')->name('purchases.edit');
    Route::post('update/{id}', 'update')->name('purchases.update');
    Route::delete('delete/{id}', 'delete')->name('purchases.delete');
    Route::get('search/by/invoice/id/{keyword}', 'searchPurchasesByInvoiceId')->name('purchases.search.by.invoice.id');

    Route::controller(PurchaseProductController::class)->prefix('products')->group(function () {

        Route::get('/', 'index')->name('purchases.products.index');
        Route::get('for/purchase/return/{purchase_id}', 'purchaseProductsForPurchaseReturn')->name('purchases.products.for.purchase.return');
    });

    Route::controller(PurchaseOrderController::class)->prefix('orders')->group(function () {
        Route::get('index/{supplierAccountId?}', 'index')->name('purchase.orders.index');
        Route::get('create', 'create')->name('purchase.orders.create');
        Route::post('store', 'store')->name('purchase.orders.store');
        Route::get('show/{id}', 'show')->name('purchase.orders.show');
        Route::get('print/{id}', 'print')->name('purchase.orders.print');
        Route::get('edit/{id}', 'edit')->name('purchase.orders.edit');
        Route::post('update/{id}', 'update')->name('purchase.orders.update');
        Route::delete('delete/{id}', 'delete')->name('purchase.orders.delete');
        Route::get('print/supplier/copy/{id}', 'printSupplierCopy')->name('purchases.order.print.supplier.copy');
    });

    Route::controller(PurchaseOrderToInvoiceController::class)->prefix('order-to-invoice')->group(function () {

        Route::get('create/{id?}', 'create')->name('purchase.order.to.invoice.create');
        Route::post('store', 'store')->name('purchase.order.to.invoice.store');
    });

    Route::controller(PurchaseReturnController::class)->prefix('returns')->group(function () {

        Route::get('/', 'index')->name('purchase.returns.index');
        Route::get('show/{id}', 'show')->name('purchase.returns.show');
        Route::get('print/{id}', 'print')->name('purchase.returns.print');
        Route::get('create', 'create')->name('purchase.returns.create');
        Route::post('store', 'store')->name('purchase.returns.store');
        Route::get('edit/{id}', 'edit')->name('purchase.returns.edit');
        Route::post('update/{id}', 'update')->name('purchase.returns.update');
        Route::delete('delete/{id}', 'delete')->name('purchase.returns.delete');
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

        Route::controller(PurchaseReturnReportController::class)->prefix('purchase-returns')->group(function () {

            Route::get('/', 'index')->name('reports.purchase.returns.index');
            Route::get('print', 'print')->name('reports.purchase.returns.print');
        });

        Route::controller(PurchaseReturnProductReportController::class)->prefix('purchase-returned-products')->group(function () {

            Route::get('/', 'index')->name('reports.purchase.returned.products.index');
            Route::get('print', 'print')->name('reports.purchase.returned.products.print');
        });

        Route::controller(PaymentAgainstPurchaseReportController::class)->prefix('payments-against-purchase')->group(function () {

            Route::get('/', 'index')->name('reports.payment.against.purchase.report');
            Route::get('print', 'print')->name('reports.payment.against.purchase.report.print');
        });

        Route::group(['prefix' => 'sales/purchase'], function () {

            Route::get('/', [SalePurchaseReportController::class, 'index'])->name('reports.sales.purchases.index');
            Route::get('sale/purchase/amounts', [SalePurchaseReportController::class, 'salePurchaseAmounts'])->name('reports.profit.sales.purchases.amounts');
            Route::get('filter/sale/purchase/amounts', [SalePurchaseReportController::class, 'filterSalePurchaseAmounts'])->name('reports.profit.sales.filter.purchases.amounts');
            Route::get('print', [SalePurchaseReportController::class, 'printSalePurchase'])->name('reports.sales.purchases.print');
        });
    });
});
