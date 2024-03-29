<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ShortMenuController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\LoanCompanyController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\MoneyReceiptController;
use App\Http\Controllers\PosShortMenuController;
use App\Http\Controllers\Accounts\BankController;
use App\Http\Controllers\InvoiceSchemaController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\CommonAjaxCallController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\SupplierImportController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\RandomSaleReturnController;
use App\Http\Controllers\Report\TaxReportController;
use App\Http\Controllers\TransferToBranchController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BranchReceiveStockController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\TransferToWarehouseController;
use App\Http\Controllers\PurchaseOrderReceiveController;
use App\Http\Controllers\Report\SaleStatementController;
use App\Http\Controllers\Report\CustomerReportController;
use App\Http\Controllers\Report\SupplierReportController;
use App\Http\Controllers\WarehouseReceiveStockController;
use App\Http\Controllers\ImportPriceGroupProductController;
use App\Http\Controllers\Report\ProfitLossReportController;
use App\Http\Controllers\AccountingRelatedSectionController;
use App\Http\Controllers\Report\ProductSaleReportController;
use App\Http\Controllers\Report\SalePaymentReportController;
use App\Http\Controllers\Report\CashRegisterReportController;
use App\Http\Controllers\Report\SaleReturnStatementController;
use App\Http\Controllers\TransferStockBranchToBranchController;
use App\Http\Controllers\Report\FinancialReportControllerReport;
use App\Http\Controllers\ReceiveTransferBranchToBranchController;
use App\Http\Controllers\Report\SaleRepresentativeReportController;


Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
Route::get('maintenance/mode', fn () => view('maintenance/maintenance'))->name('maintenance.mode');


Route::group(['prefix' => 'common/ajax/call'], function () {
    Route::get('branch/authenticated/users/{branchId}', [CommonAjaxCallController::class, 'branchAuthenticatedUsers']);
    Route::get('category/subcategories/{categoryId}', [CommonAjaxCallController::class, 'categorySubcategories']);
    Route::get('only/search/product/for/reports/{product_name}', [CommonAjaxCallController::class, 'onlySearchProductForReports'])->name('common.ajax.call.search.products.only.for.report.filter');
    Route::get('search/final/sale/invoices/{invoiceId}', [CommonAjaxCallController::class, 'searchFinalSaleInvoices']);
    Route::get('get/sale/products/{saleId}', [CommonAjaxCallController::class, 'getSaleProducts']);
    Route::get('customer_info/{customerId}', [CommonAjaxCallController::class, 'customerInfo']);
    Route::get('recent/sales/{create_by}', [CommonAjaxCallController::class, 'recentSale']);
    Route::get('recent/quotations/{create_by}', [CommonAjaxCallController::class, 'recentQuotations']);
    Route::get('recent/drafts/{create_by}', [CommonAjaxCallController::class, 'recentDrafts']);
    Route::get('branch/warehouse/{branch_id}', [CommonAjaxCallController::class, 'branchWarehouses']);
    Route::get('branch/allow/login/users/{branchId}', [CommonAjaxCallController::class, 'branchAllowLoginUsers']);
    Route::get('branch/users/{branchId}', [CommonAjaxCallController::class, 'branchUsers']);
    Route::get('get/supplier/{supplierId}', [CommonAjaxCallController::class, 'getSupplier']);
    Route::get('get/last/id/{table}/{placeholderLimit}', [CommonAjaxCallController::class, 'getLastId'])->name('common.ajax.call.get.last.id');
});

//Product section route group
Route::group(['prefix' => 'product'], function () {

    // Products route group
    Route::group(['prefix' => '/'], function () {

        // Route::get('all', [ProductController::class, 'allProduct'])->name('products.all.product');
        // Route::get('view/{productId}', [ProductController::class, 'view'])->name('products.view');
        // Route::get('get/all/product', [ProductController::class, 'getAllProduct'])->name('products.get.all.product');
        // Route::get('add', [ProductController::class, 'create'])->name('products.add.view');
        // Route::post('store', [ProductController::class, 'store'])->name('products.add.store');
        // Route::get('edit/{productId}', [ProductController::class, 'edit'])->name('products.edit');
        // Route::get('product/variants/{productId}', [ProductController::class, 'getProductVariants'])->name('products.get.product.variants');
        // Route::get('combo/product/{productId}', [ProductController::class, 'getComboProducts'])->name('products.get.combo.products');
        // Route::post('update/{productId}', [ProductController::class, 'update'])->name('products.update');
        // Route::get('default/profit', [ProductController::class, 'defaultProfit'])->name('products.add.get.default.profit');
        // Route::delete('delete/{productId}', [ProductController::class, 'delete'])->name('products.delete');
        // Route::delete('multiple/delete', [ProductController::class, 'multipleDelete'])->name('products.multiple.delete');
        // Route::get('all/form/variant', [ProductController::class, 'getAllFormVariants'])->name('products.add.get.all.from.variant');
        // Route::get('search/product/{productCode}', [ProductController::class, 'searchProduct']);
        // Route::get('get/product/stock/{productId}', [ProductController::class, 'getProductStock']);
        // Route::get('change/status/{productId}', [ProductController::class, 'changeStatus'])->name('products.change.status');
        // Route::get('check/purchase/generate/barcode/{productId}', [ProductController::class, 'chackPurchaseAndGenerateBarcode'])->name('products.check.purchase.and.generate.barcode');
        // Route::get('get/opening/stock/{productId}', [ProductController::class, 'openingStock'])->name('products.opening.stock');
        // Route::get('add/price/groups/{productId}/{type}', [ProductController::class, 'addPriceGroup'])->name('products.add.price.groups');
        // Route::post('save/price/groups', [ProductController::class, 'savePriceGroup'])->name('products.save.price.groups');
        // Route::post('opening/stock/update', [ProductController::class, 'openingStockUpdate'])->name('products.opening.stock.update');
        // Route::post('add/category', [ProductController::class, 'addCategory'])->name('products.add.category');
        // Route::post('add/brand', [ProductController::class, 'addBrand'])->name('products.add.brand');
        // Route::post('add/unit', [ProductController::class, 'addUnit'])->name('products.add.unit');
        // Route::post('add/warranty', [ProductController::class, 'addWarranty'])->name('products.add.warranty');

        // Route::get('expired/products', [ProductController::class, 'expiredProducts'])->name('products.expired.products');

        Route::group(['prefix' => 'import/price/group/products'], function () {

            Route::get('export', [ImportPriceGroupProductController::class, 'export'])->name('products.export.price.group.products');
        });
    });

    // Barcode route group

    // Import product route group
});

// Contact route group
Route::group(['prefix' => 'contacts'], function () {
    // Supplier route group
    Route::group(['prefix' => 'suppliers'], function () {

        // Route::get('/', [SupplierController::class, 'index'])->name('contacts.supplier.index');
        // Route::get('add', [SupplierController::class, 'create'])->name('contacts.supplier.create');
        // Route::post('store', [SupplierController::class, 'store'])->name('contacts.supplier.store');
        // Route::get('edit/{supplierId}', [SupplierController::class, 'edit'])->name('contacts.supplier.edit');
        // Route::post('update', [SupplierController::class, 'update'])->name('contacts.supplier.update');
        // Route::delete('delete/{supplierId}', [SupplierController::class, 'delete'])->name('contacts.supplier.delete');
        // Route::get('change/status/{supplierId}', [SupplierController::class, 'changeStatus'])->name('contacts.supplier.change.status');
        // Route::get('view/{supplierId}', [SupplierController::class, 'view'])->name('contacts.supplier.view');
        // Route::get('uncompleted/orders/{supplierId}', [SupplierController::class, 'uncompletedOrders'])->name('suppliers.uncompleted.orders');
        // Route::get('ledgers/{supplierId}', [SupplierController::class, 'ledgers'])->name('contacts.supplier.ledgers');
        // Route::get('print/ledger/{supplierId}', [SupplierController::class, 'ledgerPrint'])->name('contacts.supplier.ledger.print');
        // Route::get('all/payment/list/{supplierId}', [SupplierController::class, 'allPaymentList'])->name('suppliers.all.payment.list');
        // Route::get('all/payment/print/{supplierId}', [SupplierController::class, 'allPaymentPrint'])->name('suppliers.all.payment.print');
        // Route::get('payment/{supplierId}', [SupplierController::class, 'payment'])->name('suppliers.payment');
        // Route::post('payment/{supplierId}', [SupplierController::class, 'paymentAdd'])->name('suppliers.payment.add');
        // Route::get('return/payment/{supplierId}', [SupplierController::class, 'returnPayment'])->name('suppliers.return.payment');
        // Route::post('return/payment/{supplierId}', [SupplierController::class, 'returnPaymentAdd'])->name('suppliers.return.payment.add');
        // Route::get('payment/details/{paymentId}', [SupplierController::class, 'paymentDetails'])->name('suppliers.view.details');
        // Route::delete('payment/delete/{paymentId}', [SupplierController::class, 'paymentDelete'])->name('suppliers.payment.delete');
        // Route::get('amountsBranchWise/{supplierId}', [SupplierController::class, 'supplierAmountsBranchWise'])->name('contacts.supplier.amounts.branch.wise');
    });

    // Customers route group
    Route::group(['prefix' => 'customers'], function () {

        // Route::get('/', [CustomerController::class, 'index'])->name('contacts.customer.index');
        // Route::get('add', [CustomerController::class, 'create'])->name('contacts.customer.create');
        // Route::post('store', [CustomerController::class, 'store'])->name('contacts.customer.store');
        // Route::post('addOpeningBalance', [CustomerController::class, 'addOpeningBalance'])->name('contacts.customer.add.opening.balance');
        // Route::get('edit/{customerId}', [CustomerController::class, 'edit'])->name('contacts.customer.edit');
        // Route::post('update', [CustomerController::class, 'update'])->name('contacts.customer.update');
        // Route::delete('delete/{customerId}', [CustomerController::class, 'delete'])->name('contacts.customer.delete');
        // Route::get('change/status/{customerId}', [CustomerController::class, 'changeStatus'])->name('contacts.customer.change.status');
        // Route::get('view/{customerId}', [CustomerController::class, 'view'])->name('contacts.customer.view');
        // Route::get('ledgers/list/{customerId}', [CustomerController::class, 'ledgerList'])->name('contacts.customer.ledger.list');
        // Route::get('print/ledger/{customerId}', [CustomerController::class, 'ledgerPrint'])->name('contacts.customer.ledger.print');
        // Route::get('payment/{customerId}', [CustomerController::class, 'payment'])->name('customers.payment');
        // Route::post('payment/{customerId}', [CustomerController::class, 'paymentAdd'])->name('customers.payment.add');

        // Route::get('return/payment/{customerId}', [CustomerController::class, 'returnPayment'])->name('customers.return.payment');
        // Route::post('return/payment/{customerId}', [CustomerController::class, 'returnPaymentAdd'])->name('customers.return.payment.add');

        // Route::get('all/payment/list/{customerId}', [CustomerController::class, 'allPaymentList'])->name('customers.all.payment.list');
        // Route::get('all/payment/print/{customerId}', [CustomerController::class, 'allPaymentPrint'])->name('customers.all.payment.print');
        // Route::get('payment/details/{paymentId}', [CustomerController::class, 'paymentDetails'])->name('customers.view.details');
        // Route::delete('payment/delete/{paymentId}', [CustomerController::class, 'paymentDelete'])->name('customers.payment.delete');
        // Route::get('amountsBranchWise/{customerId}', [CustomerController::class, 'customerAmountsBranchWise'])->name('contacts.customer.amounts.branch.wise');

        // Route::group(['prefix' => 'money/receipt'], function () {
        //     Route::get('/voucher/list/{customerId}', [MoneyReceiptController::class, 'moneyReceiptList'])->name('money.receipt.voucher.list');
        //     Route::get('create/{customerId}', [MoneyReceiptController::class, 'moneyReceiptCreate'])->name('money.receipt.voucher.create');
        //     Route::post('store/{customerId}', [MoneyReceiptController::class, 'store'])->name('money.receipt.voucher.store');
        //     Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('money.receipt.voucher.edit');
        //     Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('money.receipt.voucher.update');
        //     Route::get('voucher/print/{receiptId}', [MoneyReceiptController::class, 'moneyReceiptPrint'])->name('money.receipt.voucher.print');
        //     Route::get('voucher/status/change/modal/{receiptId}', [MoneyReceiptController::class, 'changeStatusModal'])->name('money.receipt.voucher.status.change.modal');
        //     Route::post('voucher/status/change/{receiptId}', [MoneyReceiptController::class, 'changeStatus'])->name('money.receipt.voucher.status.change');
        //     Route::delete('voucher/delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('money.receipt.voucher.delete');
        // });
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'suppliers'], function () {
            Route::get('/', [SupplierReportController::class, 'index'])->name('reports.supplier.index');
            Route::get('print', [SupplierReportController::class, 'print'])->name('reports.supplier.print');
        });

        Route::group(['prefix' => 'customers'], function () {
            Route::get('/', [CustomerReportController::class, 'index'])->name('reports.customer.index');
            Route::get('print', [CustomerReportController::class, 'print'])->name('reports.customer.print');
        });
    });
});

// Purchase route group
Route::group(['prefix' => 'purchases'], function () {

    Route::group(['prefix' => '/'], function () {
        Route::get('po/process/receive/{purchaseId}', [PurchaseOrderReceiveController::class, 'processReceive'])->name('purchases.po.receive.process');
        Route::post('po/process/receive/store/{purchaseId}', [PurchaseOrderReceiveController::class, 'processReceiveStore'])->name('purchases.po.receive.process.store');
    });
});

// Sale route group sales/recent/sales
Route::group(['prefix' => 'sales'], function () {

    // Route::get('v2', [SaleController::class, 'index2'])->name('sales.index2');
    // Route::get('pos/list', [SaleController::class, 'posList'])->name('sales.pos.list');
    // Route::get('product/list', [SaleController::class, 'soldProductList'])->name('sales.product.list');
    // // Route::get('show/{saleId}', [SaleController::class, 'show'])->name('sales.show');
    // Route::get('pos/show/{saleId}', [SaleController::class, 'posShow'])->name('sales.pos.show');
    // Route::get('print/{saleId}', [SaleController::class, 'print'])->name('sales.print');
    // Route::get('packing/Slip/{saleId}', [SaleController::class, 'packingSlip'])->name('sales.packing.slip');
    // Route::get('drafts', [SaleController::class, 'drafts'])->name('sales.drafts');
    // Route::get('draft/details/{draftId}', [SaleController::class, 'draftDetails'])->name('sales.drafts.details');
    // Route::get('sales/order/list', [SaleController::class, 'salesOrderList'])->name('sales.order.list');
    // Route::get('quotations', [SaleController::class, 'quotations'])->name('sales.quotations');
    // Route::get('quotation/details/{quotationId}', [SaleController::class, 'quotationDetails'])->name('sales.quotations.details');
    // Route::get('create', [SaleController::class, 'create'])->name('sales.create');
    // Route::post('store', [SaleController::class, 'store'])->name('sales.store');
    // Route::get('edit/{saleId}', [SaleController::class, 'edit'])->name('sales.edit');
    // Route::post('update/{saleId}', [SaleController::class, 'update'])->name('sales.update');
    // Route::get('get/all/customer', [SaleController::class, 'getAllCustomer'])->name('sales.get.all.customer');
    // Route::get('get/all/users', [SaleController::class, 'getAllUser'])->name('sales.get.all.users');
    // Route::get('get/all/unit', [SaleController::class, 'getAllUnit'])->name('sales.get.all.unites');
    // Route::get('get/all/tax', [SaleController::class, 'getAllTax'])->name('sales.get.all.taxes');
    // Route::get('search/product/{status}/{product_code}/{price_group_id}/{warehouse_id}', [SaleController::class, 'searchProduct']);
    // Route::delete('delete/{saleId}', [SaleController::class, 'delete'])->name('sales.delete');
    // Route::get('edit/shipment/{saleId}', [SaleController::class, 'editShipment'])->name('sales.shipment.edit');
    // Route::post('update/shipment/{saleId}', [SaleController::class, 'updateShipment'])->name('sales.shipment.update');
    // Route::post('change/status/{saleId}', [SaleController::class, 'changeStatus'])->name('sales.change.status');
    // Route::get('check/branch/variant/qty/{status}/{product_id}/{variant_id}/{price_group_id}/{warehouse_id}', [SaleController::class, 'checkVariantProductStock']);
    // Route::get('check/single/product/stock/{status}/{product_id}/{price_group_id}/{warehouse_id}', [SaleController::class, 'checkSingleProductStock']);

    // Route::get('shipments', [SaleController::class, 'shipments'])->name('sales.shipments');

    // Sale payment route
    // Route::get('payment/{saleId}', [SaleController::class, 'paymentModal'])->name('sales.payment.modal');
    // Route::post('payment/add/{saleId}', [SaleController::class, 'paymentAdd'])->name('sales.payment.add');

    // Route::get('payment/view/{saleId}', [SaleController::class, 'viewPayment'])->name('sales.payment.view');
    // Route::get('payment/edit/{paymentId}', [SaleController::class, 'paymentEdit'])->name('sales.payment.edit');
    // Route::post('payment/update/{paymentId}', [SaleController::class, 'paymentUpdate'])->name('sales.payment.update');
    // Route::get('payment/details/{paymentId}', [SaleController::class, 'paymentDetails'])->name('sales.payment.details');

    // Route::delete('payment/delete/{paymentId}', [SaleController::class, 'paymentDelete'])->name('sales.payment.delete');

    // Route::get('return/payment/{saleId}', [SaleController::class, 'returnPaymentModal'])->name('sales.return.payment.modal');
    // Route::post('return/payment/add/{saleId}', [SaleController::class, 'returnPaymentAdd'])->name('sales.return.payment.add');
    // Route::get('return/payment/edit/{paymentId}', [SaleController::class, 'returnPaymentEdit'])->name('sales.return.payment.edit');
    // Route::post('return/payment/update/{paymentId}', [SaleController::class, 'returnPaymentUpdate'])->name('sales.return.payment.update');

    // Route::get('add/product/modal/view', [SaleController::class, 'addProductModalView'])->name('sales.add.product.modal.view');
    // Route::post('add/product', [SaleController::class, 'addProduct'])->name('sales.add.product');
    // Route::get('get/recent/product/{product_id}', [SaleController::class, 'getRecentProduct']);
    // Route::get('get/product/price/group', [SaleController::class, 'getProductPriceGroup'])->name('sales.product.price.groups');

    // Route::get('notification/form/{saleId}', [SaleController::class, 'getNotificationForm'])->name('sales.notification.form');

    // Sale return route
    // Route::group(['prefix' => 'returns'], function () {

    //     Route::get('/', [SaleReturnController::class, 'index'])->name('sales.returns.index');
    //     Route::get('show/{returnId}', [SaleReturnController::class, 'show'])->name('sales.returns.show');

    //     Route::delete('delete/{saleReturnId}', [SaleReturnController::class, 'delete'])->name('sales.returns.delete');
    //     Route::get('payment/list/{returnId}', [SaleReturnController::class, 'returnPaymentList'])->name('sales.returns.payment.list');

    //     Route::group(['prefix' => 'random'], function () {

    //         Route::get('create', [RandomSaleReturnController::class, 'create'])->name('sale.return.random.create');
    //         Route::post('store', [RandomSaleReturnController::class, 'store'])->name('sale.return.random.store');
    //         Route::get('edit/{returnId}', [RandomSaleReturnController::class, 'edit'])->name('sale.return.random.edit');
    //         Route::post('update/{returnId}', [RandomSaleReturnController::class, 'update'])->name('sale.return.random.update');
    //         Route::get('search/product/{product_code}', [RandomSaleReturnController::class, 'searchProduct']);
    //     });
    // });

    //Pos cash register routes

    // Pos routes

    // Route::group(['prefix' => 'reports'], function () {

    //     Route::group(['prefix' => 'sold/products'], function () {

    //         Route::get('/', [ProductSaleReportController::class, 'index'])->name('reports.product.sales.index');
    //         Route::get('print', [ProductSaleReportController::class, 'print'])->name('reports.product.sales.print');
    //     });

    //     Route::group(['prefix' => 'received/payments'], function () {

    //         Route::get('/', [SalePaymentReportController::class, 'index'])->name('reports.sale.payments.index');
    //         Route::get('print', [SalePaymentReportController::class, 'print'])->name('reports.sale.payments.print');
    //     });

    //     Route::group(['prefix' => 'cash/registers'], function () {

    //         Route::get('/', [CashRegisterReportController::class, 'index'])->name('reports.cash.registers.index');
    //         Route::get('get', [CashRegisterReportController::class, 'getCashRegisterReport'])->name('reports.get.cash.registers');
    //         Route::get('details/{cashRegisterId}', [CashRegisterReportController::class, 'detailsCashRegister'])->name('reports.get.cash.register.details');
    //         Route::get('report/print', [CashRegisterReportController::class, 'reportPrint'])->name('reports.get.cash.register.report.print');
    //     });

    //     Route::group(['prefix' => 'sale/representative'], function () {

    //         Route::get('/', [SaleRepresentativeReportController::class, 'index'])->name('reports.sale.representative.index');
    //         Route::get('expenses', [SaleRepresentativeReportController::class, 'SaleRepresentativeExpenseReport'])->name('reports.sale.representative.expenses');
    //     });

    //     Route::group(['prefix' => 'sale/statements'], function () {

    //         Route::get('/', [SaleStatementController::class, 'index'])->name('reports.sale.statement.index');
    //         Route::get('print', [SaleStatementController::class, 'print'])->name('reports.sale.statement.print');
    //     });

    //     Route::group(['prefix' => 'return/statements'], function () {

    //         Route::get('/', [SaleReturnStatementController::class, 'index'])->name('reports.sale.return.statement.index');
    //         Route::get('print', [SaleReturnStatementController::class, 'print'])->name('reports.sale.return.statement.print');
    //     });
    // });
});

Route::group(['prefix' => 'accounting'], function () {

    // Route::group(['prefix' => 'banks'], function () {
    //     Route::get('/', [BankController::class, 'index'])->name('accounting.banks.index');
    //     Route::post('store', [BankController::class, 'store'])->name('accounting.banks.store');
    //     Route::get('edit/{id}', [BankController::class, 'edit'])->name('accounting.banks.edit');
    //     Route::post('update/{id}', [BankController::class, 'update'])->name('accounting.banks.update');
    //     Route::delete('delete/{id}', [BankController::class, 'delete'])->name('accounting.banks.delete');
    // });

    // Route::group(['prefix' => 'accounts'], function () {

    //     Route::get('/', [AccountController::class, 'index'])->name('accounting.accounts.index');
    //     Route::get('account/book/{accountId}', [AccountController::class, 'accountBook'])->name('accounting.accounts.book');
    //     Route::get('account/ledger/print/{accountId}', [AccountController::class, 'ledgerPrint'])->name('accounting.accounts.ledger.print');
    //     Route::post('store', [AccountController::class, 'store'])->name('accounting.accounts.store');
    //     Route::get('edit/{id}', [AccountController::class, 'edit'])->name('accounting.accounts.edit');
    //     Route::post('update/{id}', [AccountController::class, 'update'])->name('accounting.accounts.update');
    //     Route::delete('delete/{accountId}', [AccountController::class, 'delete'])->name('accounting.accounts.delete');
    // });

    // Route::group(['prefix' => 'contras'], function () {

    //     Route::get('/', [ContraController::class, 'index'])->name('accounting.contras.index');
    //     Route::get('create', [ContraController::class, 'create'])->name('accounting.contras.create');
    //     Route::get('show/{contraId}', [ContraController::class, 'show'])->name('accounting.contras.show');
    //     Route::get('account/book/{contraId}', [ContraController::class, 'accountBook'])->name('accounting.contras.book');
    //     Route::post('store', [ContraController::class, 'store'])->name('accounting.contras.store');
    //     Route::get('edit/{contraId}', [ContraController::class, 'edit'])->name('accounting.contras.edit');
    //     Route::post('update/{contraId}', [ContraController::class, 'update'])->name('accounting.contras.update');
    //     Route::delete('delete/{contraId}', [ContraController::class, 'delete'])->name('accounting.contras.delete');
    // });

    Route::group(['prefix' => '/'], function () {

        Route::get('balance/sheet', [AccountingRelatedSectionController::class, 'balanceSheet'])->name('accounting.balance.sheet');
        Route::get('balance/sheet/amounts', [AccountingRelatedSectionController::class, 'balanceSheetAmounts'])->name('accounting.balance.sheet.amounts');

        Route::get('trial/balance', [AccountingRelatedSectionController::class, 'trialBalance'])->name('accounting.trial.balance');
        Route::get('trial/balance/amounts', [AccountingRelatedSectionController::class, 'trialBalanceAmounts'])->name('accounting.trial.balance.amounts');

        Route::get('cash/flow', [AccountingRelatedSectionController::class, 'cashFow'])->name('accounting.cash.flow');
        Route::get('cash/flow/amounts', [AccountingRelatedSectionController::class, 'cashFlowAmounts'])->name('accounting.cash.flow.amounts');
        Route::get('filter/cash/flow', [AccountingRelatedSectionController::class, 'filterCashflow'])->name('accounting.filter.cash.flow');
        Route::get('print/cash/flow', [AccountingRelatedSectionController::class, 'printCashflow'])->name('accounting.print.cash.flow');

        Route::get('profit/loss/account', [AccountingRelatedSectionController::class, 'profitLossAccount'])->name('accounting.profit.loss.account');
        Route::get('profit/loss/account/amounts', [AccountingRelatedSectionController::class, 'profitLossAccountAmounts'])->name('accounting.profit.loss.account.amounts');
        Route::get('print/profit/loss/account', [AccountingRelatedSectionController::class, 'printProfitLossAccount'])->name('accounting.profit.loss.account.print');
    });

    Route::group(['prefix' => 'assets'], function () {

        Route::get('/', [AssetController::class, 'index'])->name('accounting.assets.index');
        Route::post('asset/type/store', [AssetController::class, 'assetTypeStore'])->name('accounting.assets.asset.type.store');
        Route::get('asset/type/edit/{typeId}', [AssetController::class, 'assetTypeEdit'])->name('accounting.assets.asset.type.edit');
        Route::post('asset/type/update/{typeId}', [AssetController::class, 'assetTypeUpdate'])->name('accounting.assets.asset.type.update');

        Route::delete('asset/type/delete/{typeId}', [AssetController::class, 'assetTypeDelete'])->name('accounting.assets.asset.type.delete');
        Route::get('form/asset/types', [AssetController::class, 'formAssetTypes'])->name('accounting.assets.form.asset.type');

        Route::get('all/asset', [AssetController::class, 'allAsset'])->name('accounting.assets.all');
        Route::post('asset/store', [AssetController::class, 'assetStore'])->name('accounting.assets.store');
        Route::get('asset/edit/{assetId}', [AssetController::class, 'assetEdit'])->name('accounting.assets.edit');
        Route::post('asset/update/{assetId}', [AssetController::class, 'assetUpdate'])->name('accounting.assets.update');
        Route::delete('asset/delete/{assetId}', [AssetController::class, 'assetDelete'])->name('accounting.assets.delete');
    });

    Route::group(['prefix' => 'loans'], function () {

        Route::group(['prefix' => '/'], function () {

            Route::get('/', [LoanController::class, 'index'])->name('accounting.loan.index');
            Route::post('store', [LoanController::class, 'store'])->name('accounting.loan.store');
            Route::get('show/{loanId}', [LoanController::class, 'show'])->name('accounting.loan.show');
            Route::get('edit/{loanId}', [LoanController::class, 'edit'])->name('accounting.loan.edit');
            Route::post('update/{loanId}', [LoanController::class, 'update'])->name('accounting.loan.update');
            Route::delete('delete/{loanId}', [LoanController::class, 'delete'])->name('accounting.loan.delete');
            Route::get('all/companies/for/form', [LoanController::class, 'allCompaniesForForm'])->name('accounting.loan.all.companies.for.form');
            Route::get('loan/print', [LoanController::class, 'loanPrint'])->name('accounting.loan.print');
        });

        Route::group(['prefix' => 'companies'], function () {

            Route::get('/', [LoanCompanyController::class, 'index'])->name('accounting.loan.companies.index');
            Route::post('store', [LoanCompanyController::class, 'store'])->name('accounting.loan.companies.store');
            Route::get('edit/{companyId}', [LoanCompanyController::class, 'edit'])->name('accounting.loan.companies.edit');
            Route::post('update/{companyId}', [LoanCompanyController::class, 'update'])->name('accounting.loan.companies.update');
            Route::delete('delete/{companyId}', [LoanCompanyController::class, 'delete'])->name('accounting.loan.companies.delete');
        });

        Route::group(['prefix' => 'payments'], function () {

            Route::get('due/receive/modal/{company_id}', [LoanPaymentController::class, 'loanAdvanceReceiveModal'])->name('accounting.loan.advance.receive.modal');
            Route::post('due/receive/store/{company_id}', [LoanPaymentController::class, 'loanAdvanceReceiveStore'])->name('accounting.loan.advance.receive.store');
            Route::get('due/pay/modal/{company_id}', [LoanPaymentController::class, 'loaLiabilityPaymentModal'])->name('accounting.loan.liability.payment.modal');
            Route::post('due/pay/store/{company_id}', [LoanPaymentController::class, 'loanLiabilityPaymentStore'])->name('accounting.loan.liability.payment.store');
            Route::get('payment/list/{company_id}', [LoanPaymentController::class, 'paymentList'])->name('accounting.loan.payment.list');
            Route::delete('delete/{payment_id}', [LoanPaymentController::class, 'delete'])->name('accounting.loan.payment.delete');
        });
    });

    Route::group(['prefix' => 'reports'], function () {

        // Route::group(['prefix' => 'daily/profit/loss'], function () {

        //     Route::get('/', [ProfitLossReportController::class, 'index'])->name('reports.profit.loss.index');
        //     Route::get('sale/purchase/profit', [ProfitLossReportController::class, 'salePurchaseProfit'])->name('reports.profit.sale.purchase.profit');
        //     Route::get('filter/sale/purchase/profit/filter', [ProfitLossReportController::class, 'filterSalePurchaseProfit'])->name('reports.profit.filter.sale.purchase.profit');
        //     Route::get('print', [ProfitLossReportController::class, 'printProfitLoss'])->name('reports.profit.loss.print');
        // });

        Route::group(['prefix' => 'financial'], function () {

            Route::get('/', [FinancialReportControllerReport::class, 'index'])->name('reports.financial.index');
            Route::get('amounts', [FinancialReportControllerReport::class, 'financialAmounts'])->name('reports.financial.amounts');
            Route::get('filter/amounts', [FinancialReportControllerReport::class, 'filterFinancialAmounts'])->name('reports.financial.filter.amounts');
            Route::get('report/print', [FinancialReportControllerReport::class, 'print'])->name('reports.financial.report.print');
        });
    });
});

Route::group(['prefix' => 'short-menus'], function () {

    Route::get('modal/form', [ShortMenuController::class, 'showModalForm'])->name('short.menus.modal.form');
    Route::get('show', [ShortMenuController::class, 'show'])->name('short.menus.show');
    Route::post('store', [ShortMenuController::class, 'store'])->name('short.menus.store');
});

Route::group(['prefix' => 'pos-short-menus'], function () {

    Route::get('modal/form', [PosShortMenuController::class, 'showModalForm'])->name('pos.short.menus.modal.form');
    Route::get('show', [PosShortMenuController::class, 'show'])->name('pos.short.menus.show');
    Route::get('edit/page/show', [PosShortMenuController::class, 'editPageShow'])->name('pos.short.menus.edit.page.show');
    Route::post('store', [PosShortMenuController::class, 'store'])->name('pos.short.menus.store');
});

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
