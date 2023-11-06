<?php

use App\Http\Controllers\Manufacturing\ManufacturingSettingController;
use App\Http\Controllers\Manufacturing\ProcessController;
use App\Http\Controllers\Manufacturing\ProcessIngredientController;
use App\Http\Controllers\Manufacturing\ProductionController;
use App\Http\Controllers\Manufacturing\Reports\IngredientReportController;
use App\Http\Controllers\Manufacturing\Reports\ProductionReportController;
use Illuminate\Support\Facades\Route;

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

        Route::controller(ProcessIngredientController::class)->prefix('ingredients')->group(function () {

            Route::get('ingredients/for/production/{processId}/{warehouseId?}', 'ingredientsForProduction')->name('manufacturing.process.ingredients.for.production');
        });
    });

    Route::controller(ProductionController::class)->prefix('productions')->group(function () {

        Route::get('/', 'index')->name('manufacturing.productions.index');
        Route::get('show/{id}', 'show')->name('manufacturing.productions.show');
        Route::get('create', 'create')->name('manufacturing.productions.create');
        Route::post('store', 'store')->name('manufacturing.productions.store');
        Route::get('edit/{id}', 'edit')->name('manufacturing.productions.edit');
        Route::post('update/{id}', 'update')->name('manufacturing.productions.update');
        Route::delete('delete/{id}', 'delete')->name('manufacturing.productions.delete');
    });

    Route::controller(ManufacturingSettingController::class)->prefix('settings')->group(function () {

        Route::get('/', 'index')->name('manufacturing.settings.index');
        Route::post('store/or/update', 'storeOrUpdate')->name('manufacturing.settings.store.or.update');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(ProductionReportController::class)->prefix('productions')->group(function () {

            Route::get('/', 'index')->name('reports.production.report.index');
            Route::get('print', 'print')->name('reports.production.report.print');
        });

        Route::controller(IngredientReportController::class)->prefix('ingredients')->group(function () {

            Route::get('/', 'index')->name('reports.ingredients.report.index');
            Route::get('print', 'print')->name('reports.ingredients.report.print');
        });
    });
});
