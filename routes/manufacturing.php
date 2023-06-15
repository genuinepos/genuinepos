<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manufacturing\ReportController;
use App\Http\Controllers\Manufacturing\ProcessController;
use App\Http\Controllers\Manufacturing\SettingsController;
use App\Http\Controllers\Manufacturing\ProductionController;

Route::group(['prefix' => 'manufacturing', 'namespace' => 'App\Http\Controllers\Manufacturing'], function ()
{
    Route::group(['prefix' => 'process'], function ()
    {
        Route::get('/', [ProcessController::class, 'index'])->name('manufacturing.process.index');
        Route::get('show/{processId}', [ProcessController::class, 'show'])->name('manufacturing.process.show');
        Route::get('create', [ProcessController::class, 'create'])->name('manufacturing.process.create');
        Route::post('store', [ProcessController::class, 'store'])->name('manufacturing.process.store');
        Route::get('edit/{processId}', [ProcessController::class, 'edit'])->name('manufacturing.process.edit');
        Route::post('update/{processId}', [ProcessController::class, 'update'])->name('manufacturing.process.update');
        Route::delete('delete/{processId}', [ProcessController::class, 'delete'])->name('manufacturing.process.delete');
    });

    Route::group(['prefix' => 'productions'], function ()
    {
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

    Route::group(['prefix' => 'settings'], function ()
    {
        Route::get('/', [SettingsController::class, 'index'])->name('manufacturing.settings.index');
        Route::post('store', [SettingsController::class, 'store'])->name('manufacturing.settings.store');
    });

    Route::group(['prefix' => 'report'], function ()
    {
        Route::get('/', [ReportController::class, 'index'])->name('manufacturing.report.index');
    });
});
