<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;

Route::controller(ProductController::class)->prefix('products')->group(function () {

    Route::get('/', 'index')->name('products.index');
    Route::get('create', 'create')->name('products.create');
    Route::post('store', 'store')->name('products.store');
    Route::get('edit/{id}', 'edit')->name('products.edit');
    Route::post('update/{id}', 'update')->name('products.update');
    Route::delete('delete/{id}', 'delete')->name('products.delete');
    Route::get('form/part/{type}', 'formPart')->name('products.form.part');
});
