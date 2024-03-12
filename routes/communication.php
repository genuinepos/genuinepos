<?php
use Illuminate\Support\Facades\Event;
use App\Events\EmailNotified;
use App\Http\Controllers\communication\email\EmailBodyController;
use App\Http\Controllers\communication\email\EmailServerController;
use App\Http\Controllers\communication\email\SendEmailController;
use App\Http\Controllers\communication\email\MenualEmailController;
use App\Http\Controllers\communication\sms\SmsBodyController;
use App\Http\Controllers\communication\sms\SmsServerController;
use App\Http\Controllers\communication\sms\SendSmsController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'communication'], function () {

    Route::group(['prefix' => 'emails'], function () {
        Route::resource('servers', EmailServerController::class);
        Route::resource('body', EmailBodyController::class);
        Route::resource('send', SendEmailController::class);
        Route::get('restore/{id}', [SendEmailController::class, 'restoreEmail'])->name('restore_email');
        Route::delete('/delete-email-multiple', [SendEmailController::class, 'deleteEmailMultiple'])->name('delete_email_multiple');
        Route::delete('/delete-email-permanent', [SendEmailController::class, 'deleteEmailPermanent'])->name('delete_email_permanent');
        Route::resource('menual', MenualEmailController::class);
    });

    Route::group(['prefix' => 'sms'], function () {
        Route::resource('sms-server', SmsServerController::class);
        Route::resource('sms-body', SmsBodyController::class);
        Route::resource('sms-send', SendSmsController::class);
        Route::get('sms-restore/{id}', [SendSmsController::class, 'restoreSms'])->name('restore_sms');
        Route::delete('/sms/delete/multiple', [SendSmsController::class, 'deleteSmsMultiple'])->name('delete_sms_multiple');
        Route::delete('/sms/delete/permanent', [SendSmsController::class, 'deleteSmsPermanent'])->name('delete_sms_permanent');
    });

});
