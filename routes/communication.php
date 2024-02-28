<?php

use App\Http\Controllers\email\EmailSettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'communication'], function () {

    Route::group(['prefix' => 'emails'], function () {
        Route::resource('servers', EmailSettingsController::class);
    });

});
