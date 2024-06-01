<?php

use App\Http\Controllers\Startup\StartupController;

Route::controller(StartupController::class)->prefix('startup')->group(function () {

    Route::get('form', 'startupFrom')->name('startup.form');
    Route::post('finish', 'finish')->name('startup.finish');
});
