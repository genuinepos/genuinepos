<?php

use App\Http\Controllers\Accounts\BankController;
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
    });

    Route::controller(AccountGroupController::class)->prefix('accounts')->group(function () {

        Route::get('/', 'index')->name('accounts.index');
        Route::get('account/book/{accountId}', [AccountController::class, 'ledger'])->name('accounts.ledger');
        Route::get('account/ledger/print/{accountId}', [AccountController::class, 'ledgerPrint'])->name('accounts.ledger.print');
        Route::post('store', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('edit/{id}', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::post('update/{id}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('delete/{accountId}', [AccountController::class, 'delete'])->name('accounts.delete');
    });
});
