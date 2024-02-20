<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;

Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {

    Route::get('/', 'index')->name('dashboard.index');
    Route::get('card/amount', 'cardData')->name('dashboard.card.data');
    Route::get('stock/alert', 'stockAlert')->name('dashboard.stock.alert');
    Route::get('sales/order', 'salesOrder')->name('dashboard.sales.order');
    Route::get('sales/due/invoices', 'salesDueInvoices')->name('dashboard.sales.due.invoices');
    Route::get('purchase/due/invoices', 'purchaseDueInvoices')->name('dashboard.purchase.due.invoices');
    Route::get('today/summery', 'todaySummery')->name('dashboard.today.summery');
    Route::get('change/lang/{lang}', 'changeLang')->name('change.lang');
});
