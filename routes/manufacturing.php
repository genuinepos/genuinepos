<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manufacturing', 'namespace' => 'App\Http\Controllers\Manufacturing'], function ()
{
    Route::group(['prefix' => 'process'], function ()
    {
        Route::get('/', 'ProcessController@index')->name('manufacturing.process.index');
        Route::get('show/{processId}', 'ProcessController@show')->name('manufacturing.process.show');
        Route::get('create', 'ProcessController@create')->name('manufacturing.process.create');
        Route::post('store', 'ProcessController@store')->name('manufacturing.process.store');
        Route::get('edit/{processId}', 'ProcessController@edit')->name('manufacturing.process.edit');
        Route::post('update/{processId}', 'ProcessController@update')->name('manufacturing.process.update');
        Route::delete('delete/{processId}', 'ProcessController@delete')->name('manufacturing.process.delete');
    });

    Route::group(['prefix' => 'settings'], function ()
    {
        Route::get('/', 'SettingsController@index')->name('manufacturing.settings.index');
        Route::post('store', 'SettingsController@store')->name('manufacturing.settings.store');
    });
});