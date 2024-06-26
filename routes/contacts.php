<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\MoneyReceiptController;
use App\Http\Controllers\Contacts\CustomerGroupController;
use App\Http\Controllers\Contacts\CustomerImportController;
use App\Http\Controllers\Contacts\ManageCustomerController;
use App\Http\Controllers\Contacts\ManageSupplierController;
use App\Http\Controllers\Contacts\SupplierImportController;
use App\Http\Controllers\Contacts\Reports\CustomerReportController;
use App\Http\Controllers\Contacts\Reports\SupplierReportController;

Route::group(['prefix' => 'contacts'], function () {

    Route::get('index/{type}', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('create/{type}', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('store/{type}', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('edit/{id}/{type}', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::post('update/{id}/{type}', [ContactController::class, 'update'])->name('contacts.update');
    Route::post('change/status/{id}', [ContactController::class, 'changeStatus'])->name('contacts.change.status');
    Route::delete('delete/{id}/{type}', [ContactController::class, 'delete'])->name('contacts.delete');

    Route::group(['prefix' => 'manage-customers'], function () {

        Route::get('index/{type}', [ManageCustomerController::class, 'index'])->name('contacts.manage.customer.index');
        Route::get('manage/{id}', [ManageCustomerController::class, 'manage'])->name('contacts.manage.customer.manage');
    });

    Route::group(['prefix' => 'manage-suppliers'], function () {

        Route::get('index/{type}', [ManageSupplierController::class, 'index'])->name('contacts.manage.supplier.index');
        Route::get('manage/{id}', [ManageSupplierController::class, 'manage'])->name('contacts.manage.supplier.manage');
    });

    Route::group(['prefix' => 'money-receipts'], function () {

        Route::get('index/{type}', [MoneyReceiptController::class, 'index'])->name('contacts.money.receipts.index');
        Route::get('create/{contactId}', [MoneyReceiptController::class, 'create'])->name('contacts.money.receipts.create');
        Route::post('store/{contactId}', [MoneyReceiptController::class, 'store'])->name('contacts.money.receipts.store');
        Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('contacts.money.receipts.edit');
        Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('contacts.money.receipts.update');
        Route::get('print/{receiptId}', [MoneyReceiptController::class, 'print'])->name('contacts.money.receipts.print');
        Route::delete('delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('contacts.money.receipts.delete');
    });

    Route::group(['prefix' => 'groups'], function () {

        Route::get('/', [CustomerGroupController::class, 'index'])->name('contacts.customers.groups.index');
        Route::get('create', [CustomerGroupController::class, 'create'])->name('contacts.customers.groups.create');
        Route::post('store', [CustomerGroupController::class, 'store'])->name('contacts.customers.groups.store');
        Route::get('edit/{id}', [CustomerGroupController::class, 'edit'])->name('contacts.customers.groups.edit');
        Route::post('update/{id}', [CustomerGroupController::class, 'update'])->name('contacts.customers.groups.update');
        Route::delete('delete/{id}', [CustomerGroupController::class, 'delete'])->name('contacts.customers.groups.delete');
    });

    Route::group(['prefix' => 'import'], function () {

        Route::group(['prefix' => 'suppliers'], function () {

            Route::get('/', [SupplierImportController::class, 'create'])->name('contacts.suppliers.import.create');
            Route::post('store', [SupplierImportController::class, 'store'])->name('contacts.suppliers.import.store');
        });

        Route::group(['prefix' => 'customers'], function () {

            Route::get('/', [CustomerImportController::class, 'create'])->name('contacts.customers.import.create');
            Route::post('store', [CustomerImportController::class, 'store'])->name('contacts.customers.import.store');
        });
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'suppliers'], function () {
            Route::get('/', [SupplierReportController::class, 'index'])->name('reports.suppliers.index');
            Route::get('print', [SupplierReportController::class, 'print'])->name('reports.suppliers.print');
        });

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomerReportController::class, 'index'])->name('reports.customers.index');
            Route::get('print', [CustomerReportController::class, 'print'])->name('reports.customers.print');
        });
    });
});
