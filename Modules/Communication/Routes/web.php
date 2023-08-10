<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\ContactController;
use Modules\Communication\Http\Controllers\ContactGroupController;
use Modules\Communication\Http\Controllers\CustomEmailController;
use Modules\Communication\Http\Controllers\EmailSettingController;
use Modules\Communication\Http\Controllers\NumberController;
use Modules\Communication\Http\Controllers\SmsController;
use Modules\Communication\Http\Controllers\SmsSettingController;
use Modules\Communication\Http\Controllers\WhatsappMessageController;

Route::group(['prefix' => 'communication', 'as' => 'communication.'], function () {
    Route::group(['prefix' => 'sms'], function () {
        Route::get('settings', [SmsSettingController::class, 'smsSettings'])->name('sms.settings');
        Route::post('settings/store', [SmsSettingController::class, 'smsSettingsStore'])->name('sms.settings.store');
    });

    Route::controller(SmsController::class)->prefix('sms')->group(function () {
        Route::get('/index', 'index')->name('sms.index');
        Route::post('/send', 'send')->name('sms.send');
        Route::get('/important/{id}/{flag}', 'important')->name('sms.important');
        Route::post('/delete/all', 'delete_all')->name('sms.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('sms.delete');
    });

    Route::controller(WhatsappMessageController::class)->prefix('whatsapp')->group(function () {
        Route::get('/index', 'index')->name('whatsapp.index');
        Route::post('/send', 'send')->name('whatsapp.send');
        Route::get('/important/{id}/{flag}', 'important')->name('whatsapp.important');
        Route::post('/delete/all', 'delete_all')->name('whatsapp.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('whatsapp.delete');
    });

    // Route::group(['prefix' => 'email'], function () {
    //     Route::get('settings', [EmailSettingController::class, 'emailSettings'])->name('email.settings');
    //     Route::post('settings/store', [EmailSettingController::class, 'emailSettingsStore'])->name('email.settings.store');
    // });

    Route::controller(EmailSettingController::class)->group(function () {
        Route::group(['prefix' => 'email'], function () {
            Route::get('settings', 'emailSettingsUI')->name('email.settings');
            Route::get('permission/on/module', 'emailPermission')->name('email.permission');
            Route::get('manual/service', 'emailManual')->name('email.manual-service');

            Route::get('setting', 'emailSettings')->name('email.setting');
            Route::post('settings/store', 'emailSettingsStore')->name('email.settings.store');

            Route::get('body', 'emailBody')->name('email.body');
            Route::get('/view/{id}', 'view')->name('email.body.view');
            Route::post('body', 'emailBodyStore')->name('email.body-format.store');
            Route::get('/important/body/{id}/{flag}', 'importantBody')->name('email.body.important');
            Route::post('/delete/all/body', 'deleteAllBody')->name('email.body.delete_all');
            Route::delete('/delete/body/{id}', 'deleteBody')->name('email.body.delete');

            Route::get('server/setup', 'emailServerSetup')->name('email.server-setup');
            Route::get('/server/edit/{id}', 'editServer')->name('email.serve.edit');
            Route::post('server/setup', 'emailServerStore')->name('email.server.store');
            Route::get('/serve/active/{id}/{flag}', 'activeServer')->name('email.server.active');
            Route::delete('/delete/serve/{id}', 'deleteServe')->name('email.serve.delete');
            Route::post('/delete/all/server', 'deleteAllServer')->name('email.server.delete_all');
        });

    });

    Route::controller(CustomEmailController::class)->prefix('email')->group(function () {
        Route::get('/index', 'index')->name('email.index');
        Route::post('/send', 'send')->name('email.send');
        Route::get('/important/{id}/{flag}', 'important')->name('email.important');
        Route::post('/delete/all', 'delete_all')->name('email.delete_all');
        Route::delete('/delete/{id}', 'delete')->name('email.delete');
    });

    Route::controller(ContactController::class)->prefix('contacts/settings/')->group(function () {
        Route::get('/index', 'index')->name('contacts.index');
    });

    Route::controller(ContactGroupController::class)->prefix('contacts/group/')->group(function () {
        Route::get('/index', 'index')->name('contacts.group.index');
        Route::post('/store', 'store')->name('contacts.group.store');
        Route::post('/update', 'update')->name('contacts.group.update');
        Route::get('/edit/{id}', 'edit')->name('contacts.group.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('contacts.group.destroy');
    });

    Route::controller(NumberController::class)->prefix('contacts/number/')->group(function () {
        Route::get('/index', 'index')->name('contacts.number.index');
        Route::post('/store', 'store')->name('contacts.number.store');
        Route::post('/update', 'update')->name('contacts.number.update');
        Route::get('/edit/{id}', 'edit')->name('contacts.number.edit');
        Route::delete('/destroy/{id}', 'destroy')->name('contacts.number.destroy');
    });
});
