<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Services\DeviceController;
use App\Http\Controllers\Services\StatusController;
use App\Http\Controllers\Services\SettingController;
use App\Http\Controllers\Services\DeviceModelController;

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
        });
    });
});