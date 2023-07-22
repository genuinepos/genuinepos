<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\ManageCustomerController;
use App\Http\Controllers\Contacts\ManageSupplierController;

Route::group(['prefix' => 'contacts'], function () {

    Route::get('index/{type}', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('create/{type}', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('store/{type}', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('edit/{id}/{type}', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('update/{id}/{type}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('delete/{id}', [ContactController::class, 'delete'])->name('contacts.delete');
    Route::post('change/status/{id}', [ContactController::class, 'changeStatus'])->name('contacts.change.status');

    Route::group(['prefix' => 'manage/customers'], function () {

        Route::get('index/{type}', [ManageCustomerController::class, 'index'])->name('contacts.manage.customer.index');
        Route::get('manage/{id}', [ManageCustomerController::class, 'manage'])->name('contacts.manage.customer.manage');
    });

    Route::group(['prefix' => 'manage/suppliers'], function () {

        Route::get('index/{type}', [ManageSupplierController::class, 'index'])->name('contacts.manage.supplier.index');
        Route::get('manage/{id}', [ManageSupplierController::class, 'manage'])->name('contacts.manage.supplier.manage');
    });
});
