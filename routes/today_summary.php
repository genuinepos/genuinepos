<?php

use App\Http\Controllers\TodaySummary\TodaySummaryController;

Route::controller(TodaySummaryController::class)->prefix('today-summary')->group(function () {

    Route::get('/', 'index')->name('today.summary.index');
    Route::get('print', 'print')->name('today.summary.print');
});
