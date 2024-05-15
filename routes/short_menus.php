<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortMenus\ShortMenuController;

Route::controller(ShortMenuController::class)->prefix('short-menus')->group(function () {

    Route::get('modal/form/{screenType}', 'showModalForm')->name('short.menus.modal.form');
    Route::get('show/{screenType}', 'show')->name('short.menus.show');
    Route::post('store/{screenType}', 'store')->name('short.menus.store');
});
