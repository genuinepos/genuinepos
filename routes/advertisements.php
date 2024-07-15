<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Advertisements\AdvertisementController;

Route::controller(AdvertisementController::class)->prefix('advertisements')->group(function () {

    Route::get('/', 'index')->name('advertisements.index');
    Route::get('show/{id}', 'show')->name('advertisements.show');
    Route::get('create', 'create')->name('advertisements.create');
    Route::post('store', 'store')->name('advertisements.store');
    Route::get('edit/{id}', 'edit')->name('advertisements.edit');
    Route::post('update/{id}', 'update')->name('advertisements.update');
    Route::delete('delete/{id}', 'delete')->name('advertisements.delete');
});
