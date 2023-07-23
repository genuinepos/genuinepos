<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\MoneyReceiptController;
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

    Route::group(['prefix' => 'money_receipts'], function () {

        Route::get('index/{type}', [MoneyReceiptController::class, 'index'])->name('contacts.money.receipts.index');
        Route::get('create/{contactId}', [MoneyReceiptController::class, 'create'])->name('contacts.money.receipts.create');
        Route::post('store/{contactId}', [MoneyReceiptController::class, 'store'])->name('money.receipt.voucher.store');
        Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('money.receipt.voucher.edit');
        Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('money.receipt.voucher.update');
        Route::get('voucher/print/{receiptId}', [MoneyReceiptController::class, 'moneyReceiptPrint'])->name('money.receipt.voucher.print');
        Route::get('voucher/status/change/modal/{receiptId}', [MoneyReceiptController::class, 'changeStatusModal'])->name('money.receipt.voucher.status.change.modal');
        Route::post('voucher/status/change/{receiptId}', [MoneyReceiptController::class, 'changeStatus'])->name('money.receipt.voucher.status.change');
        Route::delete('voucher/delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('money.receipt.voucher.delete');
    });
});
