<?php

use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\ManageCustomerController;
use App\Http\Controllers\Contacts\ManageSupplierController;
use App\Http\Controllers\Contacts\MoneyReceiptController;
use Illuminate\Support\Facades\Route;

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

    Route::group(['prefix' => 'money_receipts'], function () {

        Route::get('index/{type}', [MoneyReceiptController::class, 'index'])->name('contacts.money.receipts.index');
        Route::get('create/{contactId}', [MoneyReceiptController::class, 'create'])->name('contacts.money.receipts.create');
        Route::post('store/{contactId}', [MoneyReceiptController::class, 'store'])->name('contacts.money.receipts.store');
        Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('contacts.money.receipts.edit');
        Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('contacts.money.receipts.update');
        Route::get('print/{receiptId}', [MoneyReceiptController::class, 'print'])->name('contacts.money.receipts.print');
        Route::delete('delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('contacts.money.receipts.delete');
    });
});
