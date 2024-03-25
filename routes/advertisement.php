<?php

use App\Http\Controllers\advertisement\AdvertiseMentController;

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'advertisement'], function () {
        Route::resource('advertise', AdvertiseMentController::class);
});
