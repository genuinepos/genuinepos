<?php

use App\Http\Controllers\ChangeLocation\ChangeLocationController;

Route::controller(ChangeLocationController::class)->prefix('change-location')->group(function () {

    Route::get('/', 'index')->name('change.location.index');
    Route::post('confirm', 'confirm')->name('change.location.confirm');
});
