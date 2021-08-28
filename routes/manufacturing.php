<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manufacturing', 'namespace' => 'App\Http\Controllers\Manufacturing'], function ()
{
    Route::group(['prefix' => 'settings'], function ()
    {
        Route::get('/', 'SettingsController@index')->name('manufacturing.settings.index');
        Route::post('store', 'SettingsController@store')->name('manufacturing.settings.store');
    });
});