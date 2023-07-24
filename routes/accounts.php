<?php

use App\Http\Controllers\Accounts\AccountGroupController;

Route::group(['prefix' => 'accounting'], function () {

    Route::controller(AccountGroupController::class)->prefix('account-groups')->group(function () {

        Route::get('/', 'index')->name('account.groups.index');
        Route::get('list', 'groupList')->name('account.groups.list');
        Route::get('create', 'create')->name('account.groups.create');
        Route::post('store', 'store')->name('account.groups.store');
        Route::get('edit/{id}', 'edit')->name('account.groups.edit');
        Route::post('update/{id}', 'update')->name('account.groups.update');
        Route::delete('delete/{id}', 'delete')->name('account.groups.delete');
    });
});
