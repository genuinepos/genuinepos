<?php

use App\Http\Controllers\StockAdjustments\StockAdjustmentController;

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

        Route::get('/', [StockAdjustmentReportController::class, 'index'])->name('reports.stock.adjustments.index');
        Route::get('all/adjustments', [StockAdjustmentReportController::class, 'allAdjustments'])->name('reports.stock.adjustments.all');
        Route::get('print', [StockAdjustmentReportController::class, 'print'])->name('reports.stock.adjustments.print');
    });
});
