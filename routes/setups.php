<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setups\BranchController;
use App\Http\Controllers\Setups\InvoiceLayoutController;

Route::prefix('setups')->group(function () {

    Route::controller(BranchController::class)->prefix('branches')->group(function () {

        Route::get('/', 'index')->name('branches.index');
        Route::get('create', 'create')->name('branches.create');
        Route::post('store', 'store')->name('branches.store');
        Route::get('edit/{id}', 'edit')->name('branches.edit');
        Route::post('update/{id}', 'update')->name('branches.update');
        Route::delete('delete/{id}', 'delete')->name('branches.delete');
    });

    Route::group(['prefix' => 'invoices'], function () {

        Route::controller(InvoiceLayoutController::class)->prefix('layouts')->group(function () {

            Route::get('/', 'index')->name('invoices.layouts.index');
            Route::get('create', 'create')->name('invoices.layouts.create');
            Route::post('/', 'store')->name('invoices.layouts.store');
            Route::get('edit/{id}', 'edit')->name('invoices.layouts.edit');
            Route::post('update/{id}', 'update')->name('invoices.layouts.update');
            Route::delete('delete/{id}', 'delete')->name('invoices.layouts.delete');
            Route::get('set/default/{id}', 'setDefault')->name('invoices.layouts.set.default');
        });
    });
});
