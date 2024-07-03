<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
Route::get('maintenance/mode', fn () => view('maintenance/maintenance'))->name('maintenance.mode');

Route::group(['prefix' => 'communication'], function () {

    Route::group(['prefix' => 'email'], function () {

        Route::get('settings', [EmailController::class, 'emailSettings'])->name('communication.email.settings');

        Route::post('settings/store', [EmailController::class, 'emailSettingsStore'])->name('communication.email.settings.store');

        Route::get('settings/server/setup/design/pages', [EmailController::class, 'emailServerSetupDesignPages'])->name('communication.email.settings.server.setup.design.pages');
    });

    Route::group(['prefix' => 'sms'], function () {

        Route::get('settings', [SmsController::class, 'smsSettings'])->name('communication.sms.settings');
        Route::post('settings/store', [SmsController::class, 'smsSettingsStore'])->name('communication.sms.settings.store');

        Route::get('settings/server/setup/design/pages', [SmsController::class, 'smsServerSetupDesignPages'])->name('communication.sms.settings.server.setup.design.pages');
    });
});

Route::controller(FeedbackController::class)->group(function () {
    Route::group(['prefix' => 'feedback'], function () {
        Route::get('/', 'index')->name('feedback.index');
        Route::post('/store', 'store')->name('feedback.store');
    });
});
