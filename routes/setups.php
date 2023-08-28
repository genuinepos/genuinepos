<?php

use App\Http\Controllers\Setups\BranchController;
use App\Http\Controllers\Setups\CashCounterController;
use App\Http\Controllers\Setups\GeneralSettingController;
use App\Http\Controllers\Setups\InvoiceLayoutController;
use App\Http\Controllers\Setups\PaymentMethodController;
use App\Http\Controllers\Setups\PaymentMethodSettingsController;
use App\Http\Controllers\Setups\WarehouseController;

Route::prefix('setups')->group(function () {

    Route::controller(GeneralSettingController::class)->prefix('general-settings')->group(function () {

        Route::get('/', 'index')->name('settings.general.index');
        Route::post('business/settings', 'businessSettings')->name('settings.business.settings');
        Route::post('tax/settings', 'taxSettings')->name('settings.tax.settings');
        Route::post('product/settings', 'productSettings')->name('settings.product.settings');
        Route::post('contact/settings', 'contactSettings')->name('settings.contact.settings');
        Route::post('sale/settings', 'saleSettings')->name('settings.sale.settings');
        Route::post('pos/settings', 'posSettings')->name('settings.pos.settings');
        Route::post('purchase/settings', 'purchaseSettings')->name('settings.purchase.settings');
        Route::post('dashboard/settings', 'dashboardSettings')->name('settings.dashboard.settings');
        Route::post('prefix/settings', 'prefixSettings')->name('settings.prefix.settings');
        Route::post('system/settings', 'systemSettings')->name('settings.system.settings');
        Route::post('module/settings', 'moduleSettings')->name('settings.module.settings');
        Route::post('send/email/sms/settings', 'SendEmailSmsSettings')->name('settings.send.email.sms.settings');
        Route::post('sms/settings', 'smsSettings')->name('settings.sms.settings');
        Route::post('rp/settings', 'rewardPointSettings')->name('settings.reward.point.settings');
    });

    Route::controller(BranchController::class)->prefix('branches')->group(function () {

        Route::get('/', 'index')->name('branches.index');
        Route::get('create', 'create')->name('branches.create');
        Route::post('store', 'store')->name('branches.store');
        Route::get('edit/{id}', 'edit')->name('branches.edit');
        Route::post('update/{id}', 'update')->name('branches.update');
        Route::delete('delete/{id}', 'delete')->name('branches.delete');
    });

    Route::controller(WarehouseController::class)->prefix('warehouses')->group(function () {

        Route::get('/', 'index')->name('warehouses.index');
        Route::get('create', 'create')->name('warehouses.create');
        Route::post('store', 'store')->name('warehouses.store');
        Route::get('edit/{id}', 'edit')->name('warehouses.edit');
        Route::post('update/{id}', 'update')->name('warehouses.update');
        Route::delete('delete/{warehouseId}', 'delete')->name('warehouses.delete');
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
        Route::get('create', 'create')->name('cash.counters.create');
        Route::post('store', 'store')->name('cash.counters.store');
        Route::get('edit/{id}', 'edit')->name('cash.counters.edit');
        Route::post('update/{id}', 'update')->name('cash.counters.update');
        Route::delete('delete/{id}', 'delete')->name('cash.counters.delete');
    });
});
