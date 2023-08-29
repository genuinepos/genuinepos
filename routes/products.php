<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\UnitController;
use App\Http\Controllers\Products\ProductController;

Route::controller(ProductController::class)->prefix('products')->group(function () {

    Route::get('/', 'index')->name('products.index');
    Route::get('show/{id}', 'show')->name('products.show');
    Route::get('create', 'create')->name('products.create');
    Route::post('store', 'store')->name('products.store');
    Route::get('edit/{id}', 'edit')->name('products.edit');
    Route::post('update/{id}', 'update')->name('products.update');
    Route::delete('delete/{id}', 'delete')->name('products.delete');
    Route::get('form/part/{type}', 'formPart')->name('products.form.part');

    Route::controller(UnitController::class)->prefix('units')->group(function () {

        Route::get('/', 'index')->name('units.index');
        Route::get('create/{isAllowedMultipleUnit?}', 'create')->name('units.create');
        Route::post('store', 'store')->name('units.store');
        Route::get('edit/{id}', 'edit')->name('units.edit');
        Route::post('update/{id}', 'update')->name('units.update');
        Route::delete('delete/{id}', 'delete')->name('units.delete');
    });
});
