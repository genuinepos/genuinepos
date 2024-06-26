<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Services\DeviceController;
use App\Http\Controllers\Services\StatusController;
use App\Http\Controllers\Services\JobCardController;
use App\Http\Controllers\Services\SettingController;
use App\Http\Controllers\Services\DeviceModelController;
use App\Http\Controllers\Services\ServiceInvoiceController;

Route::prefix('services')->group(function () {

    Route::controller(SettingController::class)->prefix('settings')->group(function () {

        Route::get('/', 'index')->name('services.index');

        Route::controller(StatusController::class)->prefix('status')->group(function () {

            Route::get('status/table', 'statusTable')->name('services.settings.status.table');
            Route::get('create', 'create')->name('services.settings.status.create');
            Route::post('store', 'store')->name('services.settings.status.store');
            Route::get('edit/{id}', 'edit')->name('services.settings.status.edit');
            Route::post('update/{id}', 'update')->name('services.settings.status.update');
            Route::delete('delete/{id}', 'delete')->name('services.settings.status.delete');
        });

        Route::controller(DeviceController::class)->prefix('devices')->group(function () {

            Route::get('devices/table', 'devicesTable')->name('services.settings.devices.table');
            Route::get('create', 'create')->name('services.settings.devices.create');
            Route::post('store', 'store')->name('services.settings.devices.store');
            Route::get('edit/{id}', 'edit')->name('services.settings.devices.edit');
            Route::post('update/{id}', 'update')->name('services.settings.devices.update');
            Route::delete('delete/{id}', 'delete')->name('services.settings.devices.delete');
        });

        Route::controller(DeviceModelController::class)->prefix('device-models')->group(function () {

            Route::get('device/models/table', 'deviceModelsTable')->name('services.settings.device.models.table');
            Route::get('create', 'create')->name('services.settings.device.models.create');
            Route::post('store', 'store')->name('services.settings.device.models.store');
            Route::get('edit/{id}', 'edit')->name('services.settings.device.models.edit');
            Route::post('update/{id}', 'update')->name('services.settings.device.models.update');
            Route::delete('delete/{id}', 'delete')->name('services.settings.device.models.delete');
            Route::get('device/models/by/brand', 'deviceModelsByBrand')->name('services.settings.device.models.by.brand');
            Route::get('device/models/by/device', 'deviceModelsByDevice')->name('services.settings.device.models.by.device');
        });
    });

    Route::controller(JobCardController::class)->prefix('job-cards')->group(function () {

        Route::get('/', 'index')->name('services.job.cards.index');
        Route::get('show/{id}', 'show')->name('services.job.cards.show');
        Route::get('generate/pdf/{id}', 'generatePdf')->name('services.job.cards.generate.pdf');
        Route::get('print/{id}', 'print')->name('services.job.cards.print');
        Route::get('generate/label/{id}', 'generateLabel')->name('services.job.cards.generate.label');
        Route::get('create', 'create')->name('services.job.cards.create');
        Route::post('store', 'store')->name('services.job.cards.store');
        Route::get('edit/{id}', 'edit')->name('services.job.cards.edit');
        Route::post('update/{id}', 'update')->name('services.job.cards.update');
        Route::get('change/status/modal/{id}', 'changeStatusModal')->name('services.job.cards.change.status.modal');
        Route::post('change/status/{id}', 'changeStatus')->name('services.job.cards.change.status');
        Route::delete('delete/{id}', 'delete')->name('services.job.cards.delete');
        Route::get('no', 'jobCardNo')->name('services.job.cards.no');
    });

    Route::controller(ServiceInvoiceController::class)->prefix('invoices')->group(function () {

        Route::get('/', 'index')->name('services.invoices.index');
    });
});
