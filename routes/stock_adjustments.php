<?php

use App\Http\Controllers\StockAdjustments\StockAdjustmentController;
use App\Http\Controllers\StockAdjustments\Reports\StockAdjustmentReportController;
use App\Http\Controllers\StockAdjustments\Reports\StockAdjustedProductReportController;

Route::controller(StockAdjustmentController::class)->prefix('stock/adjustments')->group(function () {

    Route::get('/', 'index')->name('stock.adjustments.index');
    Route::get('show/{id}', 'show')->name('stock.adjustments.show');
    Route::get('create', 'create')->name('stock.adjustments.create');
    Route::post('store', 'store')->name('stock.adjustments.store');
    Route::delete('delete/{id}', 'delete')->name('stock.adjustments.delete');
    // Route::get('search/product/in/warehouse/{keyword}/{warehouse_id}', [StockAdjustmentController::class, 'searchProductInWarehouse']);
    // Route::get('search/product/{keyword}', [StockAdjustmentController::class, 'searchProduct']);

    // Route::get('check/single/product/stock/{product_id}', [StockAdjustmentController::class, 'checkSingleProductStock']);
    // Route::get('check/single/product/stock/in/warehouse/{product_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkSingleProductStockInWarehouse']);

    // Route::get('check/variant/product/stock/{product_id}/{variant_id}', [StockAdjustmentController::class, 'checkVariantProductStock']);
    // Route::get('check/variant/product/stock/in/warehouse/{product_id}/{variant_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkVariantProductStockInWarehouse']);


    Route::group(['prefix' => 'reports'], function () {

        Route::controller(StockAdjustmentReportController::class)->prefix('stock-adjustments')->group(function () {

            Route::get('/', 'index')->name('reports.stock.adjustments.report.index');
            Route::get('all/amount', 'allAmounts')->name('reports.stock.adjustments.report.all.amount');
            Route::get('print', 'print')->name('reports.stock.adjustments.report.print');
        });

        Route::controller(StockAdjustedProductReportController::class)->prefix('stock-adjusted-products')->group(function () {

            Route::get('/', 'index')->name('reports.stock.adjusted.products.report.index');
            Route::get('print', 'print')->name('reports.stock.adjusted.products.report.print');
        });
    });
});
