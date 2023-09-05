<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Purchases\PurchaseController;

Route::controller(PurchaseController::class)->prefix('purchases')->group(function () {

    Route::get('create', 'create')->name('purchases.create');
    Route::post('store', 'store')->name('purchases.store');
});
