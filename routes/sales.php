<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sales\DraftController;
use App\Http\Controllers\Sales\PosSaleController;
use App\Http\Controllers\Sales\AddSalesController;
use App\Http\Controllers\Sales\DiscountController;
use App\Http\Controllers\Sales\ShipmentController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesHelperController;
use App\Http\Controllers\Sales\SalesReturnController;
use App\Http\Controllers\Sales\SoldProductController;
use App\Http\Controllers\Sales\CashRegisterController;
use App\Http\Controllers\Sales\PosSaleExchangeController;
use App\Http\Controllers\Sales\Reports\SalesReportController;
use App\Http\Controllers\Sales\SalesOrderToInvoiceController;
use App\Http\Controllers\Sales\Reports\SalesOrderReportController;
use App\Http\Controllers\Sales\Reports\SalesReturnReportController;
use App\Http\Controllers\Sales\Reports\SoldProductReportController;
use App\Http\Controllers\Sales\Reports\SalesOrderedProductReportController;
use App\Http\Controllers\Sales\Reports\ReceivedAgainstSalesReportController;
use App\Http\Controllers\Sales\Reports\SalesReturnedProductReportController;

Route::prefix('sales')->group(function () {

    Route::controller(AddSalesController::class)->prefix('add-sale')->group(function () {

        // Route::get('index/{customerAccountId?}/{saleScreen?}', 'index')->name('sales.index');
        Route::get('/', 'index')->name('sales.index');
        Route::get('show/{id}/{pageSize?}', 'show')->name('sales.show');
        Route::get('create', 'create')->name('sales.create');
        Route::post('store', 'store')->name('sales.store');
        Route::get('edit/{id}', 'edit')->name('sales.edit');
        Route::post('update/{id}', 'update')->name('sales.update');
        Route::delete('delete/{id}', 'delete')->name('sales.delete');
        Route::get('search/by/{keyword?}', 'searchByInvoiceId')->name('sales.search.by.invoice.id');

        Route::controller(SoldProductController::class)->prefix('products')->group(function () {

            Route::get('/', 'index')->name('sale.products.index');
            Route::get('for/sales/return/{sale_id}', 'soldProductsForSalesReturn')->name('sale.products.for.sales.return');
            Route::get('for/sales/order/to/invoice/{salesOrderId}', 'saleProductsForSalesOrderToInvoice')->name('sale.products.for.sales.order.to.invoice');
        });
    });

    Route::controller(SalesHelperController::class)->prefix('helper')->group(function () {

        Route::get('sales/list/table/{customerAccountId?}', 'salesListTable')->name('sales.helper.sales.list.table');
        Route::get('pos/selectable/products', 'posSelectableProducts')->name('sales.helper.pos.selectable.products');
        Route::get('recent/transaction/modal/{initialStatus}/{saleScreenType}/{limit?}', 'recentTransactionModal')->name('sales.helper.recent.transaction.modal');
        Route::get('recent/transaction/sales/{status}/{saleScreenType}/{limit?}', 'recentSales')->name('sales.helper.recent.transaction.sales');
        Route::get('sales/related/voucher/print/{id}', 'salesRelatedVoucherPrint')->name('sales.helper.related.voucher.print');
        Route::get('print/packing/slip/{id}', 'printPackingSlip')->name('sales.helper.print.packing.slip');
        Route::get('print/delivery/note/{id}', 'printDeliveryNote')->name('sales.helper.print.delivery.note');
        Route::get('hold/invoices/modal/{limit?}', 'holdInvoicesModal')->name('sales.helper.hold.invoices.modal');
        Route::get('suspended/modal/{limit?}', 'suspendedModal')->name('sales.helper.suspended.modal');
        Route::get('product/stock/modal', 'productStockModal')->name('sales.helper.product.stock.modal');
        Route::get('sales/invoice/or/others/id/{status?}', 'salesInvoiceOrOthersId')->name('sales.helper.invoice.or.id');
    });

    Route::controller(PosSaleController::class)->prefix('pos')->group(function () {

        Route::get('create/{jobCardId?}/{saleScreenType?}', 'create')->name('sales.pos.create');
        Route::post('store', 'store')->name('sales.pos.store');
        Route::get('edit/{saleId}/{saleScreenType?}', 'edit')->name('sales.pos.edit');
        Route::post('update/{saleId}', 'update')->name('sales.pos.update');

        Route::controller(PosSaleExchangeController::class)->prefix('pos-exchange')->group(function () {

            Route::get('search/invoice', 'searchInvoice')->name('sales.pos.exchange.search.invoice');
            Route::post('prepare', 'prepareExchange')->name('sales.pos.exchange.prepare');
            Route::post('confirm', 'exchangeConfirm')->name('sales.pos.exchange.confirm');
        });
    });

    Route::controller(CashRegisterController::class)->prefix('cash-register')->group(function () {

        Route::get('/', 'index')->name('cash.register.index');
        Route::get('create/{saleId?}/{jobCardId?}/{saleScreenType?}', 'create')->name('cash.register.create');
        Route::post('store', 'store')->name('cash.register.store');
        Route::get('show/{id}', 'show')->name('cash.register.show');
        Route::get('close/{id}', 'close')->name('cash.register.close');
        Route::post('closed/{id}', 'closed')->name('cash.register.closed');
    });

    Route::controller(SalesOrderController::class)->prefix('orders')->group(function () {

        Route::get('index/{customerAccountId?}', 'index')->name('sale.orders.index');
        Route::get('show/{id}', 'show')->name('sale.orders.show');
        Route::get('edit/{id}', 'edit')->name('sale.orders.edit');
        Route::post('update/{id}', 'update')->name('sale.orders.update');
        Route::delete('delete/{id}', 'delete')->name('sale.orders.delete');
        Route::get('search/by/{keyword}', 'searchByOrderId')->name('sale.orders.search');
    });

    Route::controller(SalesOrderToInvoiceController::class)->prefix('order-to-invoice')->group(function () {

        Route::get('create/{id?}', 'create')->name('sales.order.to.invoice.create');
        Route::post('store', 'store')->name('sales.order.to.invoice.store');
    });

    Route::controller(QuotationController::class)->prefix('quotations')->group(function () {

        Route::get('index/{saleScreenType?}', 'index')->name('sale.quotations.index');
        Route::get('show/{id}', 'show')->name('sale.quotations.show');
        Route::get('edit/{id}', 'edit')->name('sale.quotations.edit');
        Route::post('update/{id}', 'update')->name('sale.quotations.update');
        Route::get('edit/status/{id}', 'editStatus')->name('sale.quotations.status.edit');
        Route::post('update/status/{id}', 'updateStatus')->name('sale.quotations.status.update');
        Route::delete('delete/{id}', 'delete')->name('sale.quotations.delete');
    });

    Route::controller(DraftController::class)->prefix('drafts')->group(function () {

        Route::get('/', 'index')->name('sale.drafts.index');
        Route::get('show/{id}', 'show')->name('sale.drafts.show');
        Route::get('edit/{id}/{saleScreen?}', 'edit')->name('sale.drafts.edit');
        Route::post('update/{id}', 'update')->name('sale.drafts.update');
        Route::delete('delete/{id}', 'delete')->name('sale.drafts.delete');
    });

    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {

        Route::get('/', 'index')->name('sale.shipments.index');
        Route::get('show/{id}', 'show')->name('sale.shipments.show');
        Route::get('edit/{id}', 'edit')->name('sale.shipments.edit');
        Route::post('update/{id}', 'update')->name('sale.shipments.update');
    });

    Route::controller(SalesReturnController::class)->prefix('returns')->group(function () {

        Route::get('/', 'index')->name('sales.returns.index');
        Route::get('show/{id}', 'show')->name('sales.returns.show');
        Route::get('print/{id}', 'print')->name('sales.returns.print');
        Route::get('create', 'create')->name('sales.returns.create');
        Route::post('store', 'store')->name('sales.returns.store');
        Route::get('edit/{id}', 'edit')->name('sales.returns.edit');
        Route::post('update/{id}', 'update')->name('sales.returns.update');
        Route::delete('delete/{id}', 'delete')->name('sales.returns.delete');
        Route::get('voucher/no', 'voucherNo')->name('sales.returns.voucher.no');
    });

    Route::controller(DiscountController::class)->prefix('discounts')->group(function () {

        Route::get('/', 'index')->name('sales.discounts.index');
        Route::get('create', 'create')->name('sales.discounts.create');
        Route::post('store', 'store')->name('sales.discounts.store');
        Route::get('edit/{id}', 'edit')->name('sales.discounts.edit');
        Route::post('update/{id}', 'update')->name('sales.discounts.update');
        Route::get('change/status/{id}', 'changeStatus')->name('sales.discounts.change.status');
        Route::delete('delete/{id}', 'delete')->name('sales.discounts.delete');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(SalesReportController::class)->prefix('sales')->group(function () {

            Route::get('/', 'index')->name('reports.sales.report.index');
            Route::get('print', 'print')->name('reports.sales.report.print');
        });

        Route::controller(SoldProductReportController::class)->prefix('sold-products')->group(function () {

            Route::get('/', 'index')->name('reports.sold.products.report.index');
            Route::get('print', 'print')->name('reports.sold.products.report.print');
        });

        Route::controller(SalesOrderReportController::class)->prefix('sales-order')->group(function () {

            Route::get('/', 'index')->name('reports.sales.order.report.index');
            Route::get('print', 'print')->name('reports.sales.order.report.print');
        });

        Route::controller(SalesOrderedProductReportController::class)->prefix('sales-ordered_products')->group(function () {

            Route::get('/', 'index')->name('reports.sales.ordered.products.report.index');
            Route::get('print', 'print')->name('reports.sales.ordered.products.report.print');
        });

        Route::controller(SalesReturnReportController::class)->prefix('sales-return')->group(function () {

            Route::get('/', 'index')->name('reports.sales.return.report.index');
            Route::get('print', 'print')->name('reports.sales.return.report.print');
        });

        Route::controller(SalesReturnedProductReportController::class)->prefix('sales-returned-products')->group(function () {

            Route::get('/', 'index')->name('reports.sales.returned.products.report.index');
            Route::get('print', 'print')->name('reports.sales.returned.products.report.print');
        });

        Route::controller(ReceivedAgainstSalesReportController::class)->prefix('received-against-sales-report')->group(function () {

            Route::get('/', 'index')->name('reports.receive.against.sales.report');
            Route::get('print', 'print')->name('reports.receive.against.sales.report.print');
        });
    });
});
