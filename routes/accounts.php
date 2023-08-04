<?php

use App\Http\Controllers\Accounts\BankController;
use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Accounts\AccountGroupController;

Route::group(['prefix' => 'accounting'], function () {

    Route::controller(BankController::class)->prefix('banks')->group(function () {

        Route::get('/', 'index')->name('banks.index');
        Route::get('create', 'create')->name('banks.create');
        Route::post('store', 'store')->name('banks.store');
        Route::get('edit/{id}', 'edit')->name('banks.edit');
        Route::post('update/{id}', 'update')->name('banks.update');
        Route::delete('delete/{id}', 'delete')->name('banks.delete');
    });

    Route::controller(AccountGroupController::class)->prefix('account-groups')->group(function () {

        Route::get('/', 'index')->name('account.groups.index');
        Route::get('list', 'groupList')->name('account.groups.list');
        Route::get('create', 'create')->name('account.groups.create');
        Route::post('store', 'store')->name('account.groups.store');
        Route::get('edit/{id}', 'edit')->name('account.groups.edit');
        Route::post('update/{id}', 'update')->name('account.groups.update');
        Route::delete('delete/{id}', 'delete')->name('account.groups.delete');
        Route::get('account/groups/branch/wise', 'accountGroupBranchWise')->name('account.groups.branch.wise');
    });

    Route::controller(AccountController::class)->prefix('accounts')->group(function () {

        Route::get('/', 'index')->name('accounts.index');
        Route::get('create', 'create')->name('accounts.create');
        Route::get('ledger/{accountId}', 'ledger')->name('accounts.ledger');
        Route::get('ledger/print/{accountId}', 'ledgerPrint')->name('accounts.ledger.print');
        Route::post('store', 'store')->name('accounts.store');
        Route::get('edit/{id}','edit')->name('accounts.edit');
        Route::post('update/{id}', 'update')->name('accounts.update');
        Route::delete('delete/{accountId}', 'delete')->name('accounts.delete');
    });
});
