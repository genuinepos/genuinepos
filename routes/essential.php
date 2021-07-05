<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'essentials', 'namespace' => 'App\Http\Controllers\Essentials'], function () {

    Route::group(['prefix' => 'workspaces'], function ()
    {
        Route::get('/', 'WorkSpaceController@index')->name('workspace.index');
        Route::post('store', 'WorkSpaceController@store')->name('workspace.store');
    });

});


