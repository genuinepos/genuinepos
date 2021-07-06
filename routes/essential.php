<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'essentials', 'namespace' => 'App\Http\Controllers\Essentials'], function () {

    Route::group(['prefix' => 'workspaces'], function ()
    {
        Route::get('/', 'WorkSpaceController@index')->name('workspace.index');
        Route::get('show/{id}', 'WorkSpaceController@show')->name('workspace.show');
        Route::post('store', 'WorkSpaceController@store')->name('workspace.store');
        Route::get('edit/{id}', 'WorkSpaceController@edit')->name('workspace.edit');
        Route::post('update/{id}', 'WorkSpaceController@update')->name('workspace.update');
        Route::delete('delete/{id}', 'WorkSpaceController@delete')->name('workspace.delete');
        

        Route::group(['prefix' => 'tasks'], function()
        {
            Route::get('{workspaceId}', 'WorkSpaceTaskController@index')->name('workspace.task.index');
            Route::post('store', 'WorkSpaceTaskController@store')->name('workspace.task.store');
            Route::get('list/{workspaceId}', 'WorkSpaceTaskController@taskList')->name('workspace.task.list');
            Route::get('assign/user/{id}', 'WorkSpaceTaskController@assignUser')->name('workspace.task.assign.user');
            Route::get('change/status/{id}', 'WorkSpaceTaskController@changeStatus')->name('workspace.task.status');
            Route::post('update', 'WorkSpaceTaskController@update');
            Route::delete('delete/{id}', 'WorkSpaceTaskController@delete')->name('workspace.task.delete');
        });
    });

});


