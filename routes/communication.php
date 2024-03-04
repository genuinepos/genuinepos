<?php

use App\Http\Controllers\email\EmailBodyController;
use App\Http\Controllers\email\EmailServerController;
use App\Http\Controllers\email\SendEmailController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'communication'], function () {

    Route::group(['prefix' => 'emails'], function () {

        Route::resource('servers', EmailServerController::class);

        Route::resource('body', EmailBodyController::class);

        Route::resource('send', SendEmailController::class);

    });

});
