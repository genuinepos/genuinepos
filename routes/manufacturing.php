<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manufacturing\ReportController;
use App\Http\Controllers\Manufacturing\ProcessController;
use App\Http\Controllers\Manufacturing\SettingsController;
use App\Http\Controllers\Manufacturing\ProductionController;
use App\Http\Controllers\Manufacturing\ManufacturingSettingController;

Route::group(['prefix' => 'manufacturing'], function () {

    Route::controller(ProcessController::class)->prefix('process')->group(function () {

        Route::get('/', 'index')->name('manufacturing.process.index');
        Route::get('show/{id}', 'show')->name('manufacturing.process.show');
        Route::get('select/product/modal', 'selectProductModal')->name('manufacturing.process.select.product.modal');
        Route::get('create', 'create')->name('manufacturing.process.create');
        Route::post('store', 'store')->name('manufacturing.process.store');
        Route::get('edit/{id}', 'edit')->name('manufacturing.process.edit');
        Route::post('update/{id}', 'update')->name('manufacturing.process.update');
        Route::delete('delete/{id}', 'delete')->name('manufacturing.process.delete');
    });

    Route::group(['prefix' => 'productions'], function () {

        Route::get('/', [ProductionController::class, 'index'])->name('manufacturing.productions.index');
        Route::get('show/{productionId}', [ProductionController::class, 'show'])->name('manufacturing.productions.show');
        Route::get('create', [ProductionController::class, 'create'])->name('manufacturing.productions.create');
        Route::post('store', [ProductionController::class, 'store'])->name('manufacturing.productions.store');
        Route::get('edit/{productionId}', [ProductionController::class, 'edit'])->name('manufacturing.productions.edit');
        Route::post('update/{productionId}', [ProductionController::class, 'update'])->name('manufacturing.productions.update');
        Route::delete('delete/{productionId}', [ProductionController::class, 'delete'])->name('manufacturing.productions.delete');
        Route::get('get/process/{processId}', [ProductionController::class, 'getProcess']);
        Route::get('get/ingredients/{processId}/{warehouseId}', [ProductionController::class, 'getIngredients']);
    });

    Route::controller(ManufacturingSettingController::class)->prefix('settings')->group(function () {

        Route::get('/', 'index')->name('manufacturing.settings.index');
        Route::post('store/or/update', 'storeOrUpdate')->name('manufacturing.settings.store.or.update');
    });

    Route::group(['prefix' => 'report'], function () {

        Route::get('/', [ReportController::class, 'index'])->name('manufacturing.report.index');
    });
});
