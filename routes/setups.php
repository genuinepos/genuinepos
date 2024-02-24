<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Setups\StartupController;
use App\Http\Controllers\Setups\BranchController;
use App\Http\Controllers\Setups\WarehouseController;
use App\Http\Controllers\Setups\CashCounterController;
use App\Http\Controllers\Setups\ReleaseNoteController;
use App\Http\Controllers\Setups\BranchSettingController;
use App\Http\Controllers\Setups\InvoiceLayoutController;
use App\Http\Controllers\Setups\PaymentMethodController;
use App\Http\Controllers\Setups\BarcodeSettingController;
use App\Http\Controllers\Setups\GeneralSettingController;
use App\Http\Controllers\Setups\PaymentMethodSettingsController;
use App\Http\Controllers\Setups\SoftwareServiceBillingController;

Route::prefix('setups')->group(function () {

    Route::controller(StartupController::class)->prefix('startup')->group(function () {

        Route::get('form', 'startupFrom')->name('setup.startup.form');
        Route::post('finish', 'finish')->name('setup.startup.finish');
    });

    Route::controller(GeneralSettingController::class)->prefix('general-settings')->group(function () {

        Route::get('/', 'index')->name('settings.general.index');
        Route::post('business/settings', 'businessSettings')->name('settings.business.settings');
        Route::post('product/settings', 'productSettings')->name('settings.product.settings');
        Route::post('add/sale/settings', 'addSaleSettings')->name('settings.add.sale.settings');
        Route::post('pos/settings', 'posSettings')->name('settings.pos.settings');
        Route::post('purchase/settings', 'purchaseSettings')->name('settings.purchase.settings');
        Route::post('manufacturing/settings', 'manufacturingSettings')->name('settings.manufacturing.settings');
        Route::post('dashboard/settings', 'dashboardSettings')->name('settings.dashboard.settings');
        Route::post('prefix/settings', 'prefixSettings')->name('settings.prefix.settings');
        Route::post('invoice/layout/settings', 'invoiceLayoutSettings')->name('settings.invoice.layout.settings');
        Route::post('print/page/size/settings', 'printPageSizeSettings')->name('settings.print.page.size.settings');
        Route::post('system/settings', 'systemSettings')->name('settings.system.settings');
        Route::post('module/settings', 'moduleSettings')->name('settings.module.settings');
        Route::post('send/email/settings', 'sendEmailSettings')->name('settings.send.email.settings');
        Route::post('send/sms/settings', 'sendSmsSettings')->name('settings.send.sms.settings');
        Route::post('rp/settings', 'rewardPointSettings')->name('settings.reward.point.settings');
    });

    Route::controller(BranchController::class)->prefix('branches')->group(function () {

        Route::get('/', 'index')->name('branches.index');
        Route::get('create', 'create')->name('branches.create');
        Route::post('store', 'store')->name('branches.store');
        Route::get('edit/{id}', 'edit')->name('branches.edit');
        Route::post('update/{id}', 'update')->name('branches.update');
        Route::delete('delete/{id}', 'delete')->name('branches.delete');
        Route::get('parent/with/child/branches/{id}', 'parentWithChildBranches')->name('branches.parent.with.child.branches');
        Route::get('branch/code/{parentBranchId?}', 'branchCode')->name('branches.code');

        Route::controller(BranchSettingController::class)->prefix('settings')->group(function () {

            Route::get('index/{id}', 'index')->name('branches.settings.index');
            Route::post('product/{id}', 'productSettings')->name('branches.settings.product');
            Route::post('add/sale/{id}', 'addSaleSettings')->name('branches.settings.add.sale');
            Route::post('pos/{id}', 'posSettings')->name('branches.settings.pos');
            Route::post('purchase/{id}', 'purchaseSettings')->name('branches.settings.purchase');
            Route::post('manufacturing/{id}', 'manufacturingSettings')->name('branches.settings.manufacturing');
            Route::post('dashboard/{id}', 'dashboardSettings')->name('branches.settings.dashboard');
            Route::post('prefix/{id}', 'prefixSettings')->name('branches.settings.prefix');
            Route::post('invoice/layout/{id}', 'invoiceLayoutSettings')->name('branches.settings.invoice.layout');
            Route::post('print/page/size/{id}', 'printPageSizeSettings')->name('branches.settings.print.page.size');
            Route::post('system/{id}', 'systemSettings')->name('branches.settings.system');
            Route::post('module/{id}', 'moduleSettings')->name('branches.settings.module');
            Route::post('send/email/{id}', 'sendEmailSettings')->name('branches.settings.send.email');
            Route::post('send/sms/{id}', 'sendSmsSettings')->name('branches.settings.sms');
            Route::post('reward/point/{id}', 'rewardPointSettings')->name('branches.settings.reward.point');
        });
    });

    Route::controller(WarehouseController::class)->prefix('warehouses')->group(function () {

        Route::get('/', 'index')->name('warehouses.index');
        Route::get('create', 'create')->name('warehouses.create');
        Route::post('store', 'store')->name('warehouses.store');
        Route::get('edit/{id}', 'edit')->name('warehouses.edit');
        Route::post('update/{id}', 'update')->name('warehouses.update');
        Route::delete('delete/{warehouseId}', 'delete')->name('warehouses.delete');
        Route::get('warehouses/by/branch/{branchId}/{isAllowGlobalWarehouse?}', 'warehousesByBranch')->name('warehouses.by.branch');
    });

    Route::group(['prefix' => 'invoices'], function () {

        Route::controller(InvoiceLayoutController::class)->prefix('layouts')->group(function () {

            Route::get('/', 'index')->name('invoices.layouts.index');
            Route::get('create', 'create')->name('invoices.layouts.create');
            Route::post('/', 'store')->name('invoices.layouts.store');
            Route::get('edit/{id}', 'edit')->name('invoices.layouts.edit');
            Route::post('update/{id}', 'update')->name('invoices.layouts.update');
            Route::delete('delete/{id}', 'delete')->name('invoices.layouts.delete');
        });
    });

    Route::controller(PaymentMethodController::class)->prefix('payment_methods')->group(function () {

        Route::get('/', 'index')->name('payment.methods.index');
        Route::get('create', 'create')->name('payment.methods.create');
        Route::post('/', 'store')->name('payment.methods.store');
        Route::get('edit/{id}', 'edit')->name('payment.methods.edit');
        Route::post('update/{id}', 'update')->name('payment.methods.update');
        Route::delete('delete/{id}', 'delete')->name('payment.methods.delete');

        Route::controller(PaymentMethodSettingsController::class)->prefix('settings')->group(function () {

            Route::get('/', 'settingsView')->name('payment.methods.settings.view');
            Route::post('update', 'update')->name('payment.methods.settings.add.or.update');
        });
    });

    Route::controller(CashCounterController::class)->prefix('cash_counter')->group(function () {

        Route::get('/', 'index')->name('cash.counters.index');
        Route::get('create', 'create')->name('cash.counters.create');
        Route::post('store', 'store')->name('cash.counters.store');
        Route::get('edit/{id}', 'edit')->name('cash.counters.edit');
        Route::post('update/{id}', 'update')->name('cash.counters.update');
        Route::delete('delete/{id}', 'delete')->name('cash.counters.delete');
    });

    Route::controller(BarcodeSettingController::class)->prefix('barcode_settings')->group(function () {

        Route::get('/', 'index')->name('barcode.settings.index');
        Route::get('create', 'create')->name('barcode.settings.create');
        Route::post('store', 'store')->name('barcode.settings.store');
        Route::get('edit/{id}', 'edit')->name('barcode.settings.edit');
        Route::post('update/{id}', 'update')->name('barcode.settings.update');
        Route::delete('delete/{id}', 'delete')->name('barcode.settings.delete');
        Route::get('set-default/{id}', 'setDefault')->name('barcode.settings.set.default');
        Route::get('design/pages', 'designPage')->name('barcode.settings.design.pages');
    });

    Route::group(['prefix' => 'release/note'], function () {

        Route::get('/', [ReleaseNoteController::class, 'index'])->name('settings.release.note.index');
    });

    Route::group(['prefix' => 'billing'], function () {

        Route::get('/', [SoftwareServiceBillingController::class, 'index'])->name('software.service.billing.index');
        Route::get('upgrade/plan', [SoftwareServiceBillingController::class, 'upgradePlan'])->name('software.service.billing.upgrade.plan');
        Route::get('cart/for/upgrade/plan/{id}', [SoftwareServiceBillingController::class, 'cartFoUpgradePlan'])->name('software.service.billing.cart.for.upgrade.plan');
        Route::post('cart/for/upgrade/plan/{id}', [SoftwareServiceBillingController::class, 'processUpgradePlan'])->name('software.service.billing.cart.for.upgrade.plan.process');
        Route::get('cart/for/add/branch', [SoftwareServiceBillingController::class, 'cartFoAddBranch'])->name('software.service.billing.cart.for.add.branch');
        Route::get('cart/for/renew/branch', [SoftwareServiceBillingController::class, 'cartForRenewBranch'])->name('software.service.billing.cart.for.renew.branch');

        Route::get('due/repayment', [SoftwareServiceBillingController::class, 'dueRepayment'])->name('software.service.billing.due.repayment');
    });
});
