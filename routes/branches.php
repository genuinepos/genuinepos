<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Branches\BranchController;
use App\Http\Controllers\Branches\BranchSettingController;

Route::controller(BranchController::class)->prefix('branches')->group(function () {

    Route::get('/', 'index')->name('branches.index');
    Route::get('create', 'create')->name('branches.create');
    Route::post('store', 'store')->name('branches.store');
    Route::get('edit/{id}', 'edit')->name('branches.edit');
    Route::post('update/{id}', 'update')->name('branches.update');
    Route::delete('delete/{id}', 'delete')->name('branches.delete');
    Route::get('parent/with/child/branches/{id}', 'parentWithChildBranches')->name('branches.parent.with.child.branches');
    Route::get('branch/code/{parentBranchId?}', 'branchCode')->name('branches.code');
    Route::delete('delete/branch/logo/{id}', 'deleteLogo')->name('branches.logo.delete');

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
        Route::post('service/{id}', 'serviceSettings')->name('branches.settings.service.settings');
        Route::post('pdf/label/{id}', 'servicePdfAndLabelSettings')->name('branches.settings.pdf.and.label.settings');
    });
});
