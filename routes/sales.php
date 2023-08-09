<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\SalesController;

Route::controller(SalesController::class)->prefix('sales')->group(function () {

    Route::get('/', 'index')->name('sales.index');
    Route::get('create', 'create')->name('sales.create');
    Route::post('store', 'store')->name('sales.store');
    Route::get('edit/{id}', 'edit')->name('sales.edit');
    Route::post('update/{id}', 'update')->name('sales.update');
    Route::delete('delete/{id}', 'delete')->name('sales.delete');
});
