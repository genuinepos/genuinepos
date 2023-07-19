<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contacts\ContactController;

Route::group(['prefix' => 'contacts'], function () {

    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('create/{type}', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('store/{type}', [ContactController::class, 'store'])->name('contacts.store');
});
