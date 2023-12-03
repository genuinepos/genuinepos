<?php

use App\Http\Controllers\TransferStocks\ReceiveStockFromBranchController;
use App\Http\Controllers\TransferStocks\ReceiveStockFromWarehouseController;
use App\Http\Controllers\TransferStocks\TransferStockController;
use Illuminate\Support\Facades\Route;

Route::controller(TransferStockController::class)->prefix('transfer-stocks')->group(function () {

    Route::get('index/{type?}', 'index')->name('transfer.stocks.index');
    Route::get('show/{id}', 'show')->name('transfer.stocks.show');
    Route::get('create', 'create')->name('transfer.stocks.create');
    Route::post('store', 'store')->name('transfer.stocks.store');
    Route::get('edit/{id}', 'edit')->name('transfer.stocks.edit');
    Route::post('update/{id}', 'update')->name('transfer.stocks.update');
    Route::delete('delete/{id}', 'delete')->name('transfer.stocks.delete');

    Route::prefix('receive-transferred-stocks')->group(function () {

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
