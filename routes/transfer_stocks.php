<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferStocks\ReceiveStockFromBranchController;
use App\Http\Controllers\TransferStocks\ReceiveStockFromWarehouseController;
use App\Http\Controllers\TransferStocks\TransferStockBranchToBranchController;
use App\Http\Controllers\TransferStocks\TransferStockBranchToWarehouseController;
use App\Http\Controllers\TransferStocks\TransferStockWarehouseToBranchController;

Route::prefix('transfer-stocks')->group(function () {

    Route::controller(TransferStockWarehouseToBranchController::class)->prefix('warehouse-to-branch')->group(function () {

        Route::get('index/{type?}', 'index')->name('transfer.stock.warehouse.to.branch.index');
        Route::get('show/{id}', 'show')->name('transfer.stock.warehouse.to.branch.show');
        Route::get('create', 'create')->name('transfer.stock.warehouse.to.branch.create');
        Route::post('store', 'store')->name('transfer.stock.warehouse.to.branch.store');
        Route::get('edit/{id}', 'edit')->name('transfer.stock.warehouse.to.branch.edit');
        Route::post('update/{id}', 'update')->name('transfer.stock.warehouse.to.branch.update');
        Route::delete('delete/{id}', 'delete')->name('transfer.stock.warehouse.to.branch.delete');
    });

    Route::controller(TransferStockBranchToWarehouseController::class)->prefix('branch-to-warehouse')->group(function () {

        Route::get('index/{type?}', 'index')->name('transfer.stock.branch.to.warehouse.index');
        Route::get('show/{id}', 'show')->name('transfer.stock.branch.to.warehouse.show');
        Route::get('create', 'create')->name('transfer.stock.branch.to.warehouse.create');
        Route::post('store', 'store')->name('transfer.stock.branch.to.warehouse.store');
        Route::get('edit/{id}', 'edit')->name('transfer.stock.branch.to.warehouse.edit');
        Route::post('update/{id}', 'update')->name('transfer.stock.branch.to.warehouse.update');
        Route::delete('delete/{id}', 'delete')->name('transfer.stock.branch.to.warehouse.delete');
    });

    Route::controller(TransferStockBranchToBranchController::class)->prefix('branch-to-branch')->group(function () {

        Route::get('index/{type?}', 'index')->name('transfer.stock.branch.to.branch.index');
        Route::get('show/{id}', 'show')->name('transfer.stock.branch.to.branch.show');
        Route::get('create', 'create')->name('transfer.stock.branch.to.branch.create');
        Route::post('store', 'store')->name('transfer.stock.branch.to.branch.store');
        Route::get('edit/{id}', 'edit')->name('transfer.stock.branch.to.branch.edit');
        Route::post('update/{id}', 'update')->name('transfer.stock.branch.to.branch.update');
        Route::delete('delete/{id}', 'delete')->name('transfer.stock.branch.to.branch.delete');
    });

    Route::prefix('receive-transferred-stock')->group(function () {

        Route::controller(ReceiveStockFromBranchController::class)->prefix('from-branch')->group(function () {

            Route::get('/', 'index')->name('receive.stock.from.branch.index');
            Route::get('create/{transferStockId}', 'create')->name('receive.stock.from.branch.create');
            Route::post('receive/{transferStockId}', 'receive')->name('receive.stock.from.branch.receive');
        });

        Route::controller(ReceiveStockFromWarehouseController::class)->prefix('from-warehouse')->group(function () {

            Route::get('/', 'index')->name('receive.stock.from.warehouse.index');
            Route::get('create/{transferStockId}', 'create')->name('receive.stock.from.warehouse.create');
            Route::post('receive/{transferStockId}', 'receive')->name('receive.stock.from.warehouse.receive');
        });
    });
});
