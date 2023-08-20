<?php

use App\Http\Controllers\Setups\BranchController;
use App\Http\Controllers\Setups\InvoiceLayoutController;
use App\Http\Controllers\Setups\PaymentMethodController;
use App\Http\Controllers\PaymentMethodSettingsController;

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

    Route::controller(PaymentMethodController::class)->prefix('payment_methods')->group(function () {

        Route::get('/', 'index')->name('payment.methods.index');
        Route::get('create', 'create')->name('payment.methods.create');
        Route::post('/', 'store')->name('payment.methods.store');
        Route::get('edit/{id}', 'edit')->name('payment.methods.edit');
        Route::post('update/{id}', 'update')->name('payment.methods.update');
        Route::delete('delete/{id}', 'delete')->name('payment.methods.delete');
    });

    Route::controller(PaymentMethodSettingsController::class)->prefix('payment_method_settings')->group(function () {

        Route::get('/', 'index')->name('payment.method.settings.index');
        Route::post('update', 'update')->name('payment.method.settings.update');
    });

    Route::controller(CashCounterController::class)->prefix('cash_counter')->group(function () {

        Route::get('/', 'index')->name('cash.counters.index');
        Route::post('store', 'store')->name('cash.counters.store');
        Route::get('edit/{id}', 'edit')->name('cash.counters.edit');
        Route::post('update/{id}', 'update')->name('cash.counters.update');
        Route::delete('delete/{id}', 'delete')->name('cash.counters.delete');
    });
});
