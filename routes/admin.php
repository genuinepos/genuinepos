<?php

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerCreditLimit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\CustomerOpeningBalance;
use App\Models\SupplierOpeningBalance;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContraController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ExpanseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortMenuController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PriceGroupController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\BulkVariantController;
use App\Http\Controllers\CashCounterController;
use App\Http\Controllers\LoanCompanyController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\ReleaseNoteController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\MoneyReceiptController;
use App\Http\Controllers\PosShortMenuController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\InvoiceLayoutController;
use App\Http\Controllers\InvoiceSchemaController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\BarcodeSettingController;
use App\Http\Controllers\CommonAjaxCallController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SupplierImportController;
use App\Http\Controllers\ExpanseCategoryController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\RandomSaleReturnController;
use App\Http\Controllers\Report\TaxReportController;
use App\Http\Controllers\TransferToBranchController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BranchReceiveStockController;
use App\Http\Controllers\Report\StockReportController;
use App\Http\Controllers\TransferToWarehouseController;
use App\Http\Controllers\PurchaseOrderReceiveController;
use App\Http\Controllers\Report\ExpanseReportController;
use App\Http\Controllers\Report\SaleStatementController;
use App\Http\Controllers\PaymentMethodSettingsController;
use App\Http\Controllers\Report\CustomerReportController;
use App\Http\Controllers\Report\SupplierReportController;
use App\Http\Controllers\WarehouseReceiveStockController;
use App\Http\Controllers\ImportPriceGroupProductController;
use App\Http\Controllers\Report\ProfitLossReportController;
use App\Http\Controllers\Report\StockInOutReportController;
use App\Http\Controllers\AccountingRelatedSectionController;
use App\Http\Controllers\Report\ProductSaleReportController;
use App\Http\Controllers\Report\PurchaseStatementController;
use App\Http\Controllers\Report\SalePaymentReportController;
use App\Http\Controllers\Report\CashRegisterReportController;
use App\Http\Controllers\Report\SalePurchaseReportController;
use App\Http\Controllers\Report\SaleReturnStatementController;
use App\Http\Controllers\TransferStockBranchToBranchController;
use App\Http\Controllers\Report\FinancialReportControllerReport;
use App\Http\Controllers\Report\ProductPurchaseReportController;
use App\Http\Controllers\Report\PurchasePaymentReportController;
use App\Http\Controllers\Report\StockAdjustmentReportController;
use App\Http\Controllers\Report\UserActivityLogReportController;
use App\Http\Controllers\ReceiveTransferBranchToBranchController;
use App\Http\Controllers\Report\SaleRepresentativeReportController;


Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
Route::get('maintenance/mode', fn () => view('maintenance/maintenance'))->name('maintenance.mode');


Route::get('change/lang/{lang}', [DashboardController::class, 'changeLang'])->name('change.lang');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.dashboard');
Route::get('dashboard/card/amount', [DashboardController::class, 'cardData'])->name('dashboard.card.data');
Route::get('dashboard/stock/alert', [DashboardController::class, 'stockAlert'])->name('dashboard.stock.alert');
Route::get('dashboard/sale/order', [DashboardController::class, 'saleOrder'])->name('dashboard.sale.order');
Route::get('dashboard/sale/due', [DashboardController::class, 'saleDue'])->name('dashboard.sale.due');
Route::get('dashboard/purchase/due', [DashboardController::class, 'purchaseDue'])->name('dashboard.purchase.due');
Route::get('dashboard/today/summery', [DashboardController::class, 'todaySummery'])->name('dashboard.today.summery');

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
});

//Product section route group
Route::group(['prefix' => 'product'], function () {
    // Branch route group
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('product.categories.index');
        Route::get('form/category', [CategoryController::class, 'getAllFormCategory'])->name('product.categories.all.category.form');
        Route::post('store', [CategoryController::class, 'store'])->name('product.categories.store');
        Route::get('edit/{categoryId}', [CategoryController::class, 'edit'])->name('product.categories.edit');
        Route::post('update', [CategoryController::class, 'update'])->name('product.categories.update');
        Route::delete('delete/{categoryId}', [CategoryController::class, 'delete'])->name('product.categories.delete');
    });

    Route::group(['prefix' => 'sub-categories'], function () {
        Route::get('/', [SubCategoryController::class, 'index'])->name('product.subcategories.index');
        Route::post('store', [SubCategoryController::class, 'store'])->name('product.subcategories.store');
        Route::post('update', [SubCategoryController::class, 'update'])->name('product.subcategories.update');
        Route::delete('delete/{categoryId}', [SubCategoryController::class, 'delete'])->name('product.subcategories.delete');
        Route::get('edit/{id}', [SubCategoryController::class, 'edit']);
    });

    // Brand route group
    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('product.brands.index');
        Route::get('all', [BrandController::class, 'getAllBrand'])->name('product.brands.all.brand');
        Route::post('store', [BrandController::class, 'store'])->name('product.brands.store');
        Route::post('update', [BrandController::class, 'update'])->name('product.brands.update');
        Route::delete('delete/{brandId}', [BrandController::class, 'delete'])->name('product.brands.delete');
        Route::get('edit/{id}', [BrandController::class, 'edit'])->name('product.brands.edit');
    });

    // Products route group
    Route::group(['prefix' => '/'], function () {
        Route::get('all', [ProductController::class, 'allProduct'])->name('products.all.product');
        Route::get('view/{productId}', [ProductController::class, 'view'])->name('products.view');
        Route::get('get/all/product', [ProductController::class, 'getAllProduct'])->name('products.get.all.product');
        Route::get('add', [ProductController::class, 'create'])->name('products.add.view');
        Route::get('get/form/part/{type}', [ProductController::class, 'getFormPart']);
        Route::post('store', [ProductController::class, 'store'])->name('products.add.store');
        Route::get('edit/{productId}', [ProductController::class, 'edit'])->name('products.edit');
        Route::get('product/variants/{productId}', [ProductController::class, 'getProductVariants'])->name('products.get.product.variants');
        Route::get('combo/product/{productId}', [ProductController::class, 'getComboProducts'])->name('products.get.combo.products');
        Route::post('update/{productId}', [ProductController::class, 'update'])->name('products.update');
        Route::get('default/profit', [ProductController::class, 'defaultProfit'])->name('products.add.get.default.profit');
        Route::delete('delete/{productId}', [ProductController::class, 'delete'])->name('products.delete');
        Route::delete('multiple/delete', [ProductController::class, 'multipleDelete'])->name('products.multiple.delete');
        Route::get('all/form/variant', [ProductController::class, 'getAllFormVariants'])->name('products.add.get.all.from.variant');
        Route::get('search/product/{productCode}', [ProductController::class, 'searchProduct']);
        Route::get('get/product/stock/{productId}', [ProductController::class, 'getProductStock']);
        Route::get('change/status/{productId}', [ProductController::class, 'changeStatus'])->name('products.change.status');
        Route::get('check/purchase/generate/barcode/{productId}', [ProductController::class, 'chackPurchaseAndGenerateBarcode'])->name('products.check.purchase.and.generate.barcode');
        Route::get('get/opening/stock/{productId}', [ProductController::class, 'openingStock'])->name('products.opening.stock');
        Route::get('add/price/groups/{productId}/{type}', [ProductController::class, 'addPriceGroup'])->name('products.add.price.groups');
        Route::post('save/price/groups', [ProductController::class, 'savePriceGroup'])->name('products.save.price.groups');
        Route::post('opening/stock/update', [ProductController::class, 'openingStockUpdate'])->name('products.opening.stock.update');
        Route::post('add/category', [ProductController::class, 'addCategory'])->name('products.add.category');
        Route::post('add/brand', [ProductController::class, 'addBrand'])->name('products.add.brand');
        Route::post('add/unit', [ProductController::class, 'addUnit'])->name('products.add.unit');
        Route::post('add/warranty', [ProductController::class, 'addWarranty'])->name('products.add.warranty');
        Route::get('settings', [ProductController::class, 'settings'])->name('products.settings');
        Route::post('settings/store', [ProductController::class, 'settingsStore'])->name('products.settings.store');

        Route::group(['prefix' => 'import/price/group/products'], function () {
            Route::get('export', [ImportPriceGroupProductController::class, 'export'])->name('products.export.price.group.products');
        });
    });

    // Selling price group route group
    Route::group(['prefix' => 'selling/price/groups'], function () {
        Route::get('/', [PriceGroupController::class, 'index'])->name('product.selling.price.groups.index');
        Route::post('store', [PriceGroupController::class, 'store'])->name('product.selling.price.groups.store');
        Route::get('edit/{id}', [PriceGroupController::class, 'edit'])->name('product.selling.price.groups.edit');
        Route::post('update/{id}', [PriceGroupController::class, 'update'])->name('product.selling.price.groups.update');
        Route::delete('delete/{id}', [PriceGroupController::class, 'delete'])->name('product.selling.price.groups.delete');
        Route::get('change/status/{id}', [PriceGroupController::class, 'changeStatus'])->name('product.selling.price.groups.change.status');
    });

    // Variants route group
    Route::group(['prefix' => 'variants'], function () {
        Route::get('/', [BulkVariantController::class, 'index'])->name('product.variants.index');
        Route::get('all', [BulkVariantController::class, 'getAllVariant'])->name('product.variants.all.variant');
        Route::post('store', [BulkVariantController::class, 'store'])->name('product.variants.store');
        Route::post('update', [BulkVariantController::class, 'update'])->name('product.variants.update');
        Route::delete('delete/{id}', [BulkVariantController::class, 'delete'])->name('product.variants.delete');
    });

    // Barcode route group
    Route::group(['prefix' => 'barcode'], function () {
        Route::get('/', [BarcodeController::class, 'index'])->name('barcode.index');
        Route::post('preview', [BarcodeController::class, 'preview'])->name('barcode.preview');
        Route::get('supplier/products', [BarcodeController::class, 'supplierProduct'])->name('barcode.supplier.get.products');
        Route::post('multiple/generate/completed', [BarcodeController::class, 'multipleGenerateCompleted'])->name('barcode.multiple.generate.completed');
        Route::get('search/product/{searchKeyword}', [BarcodeController::class, 'searchProduct']);
        Route::get('get/selected/product/{productId}', [BarcodeController::class, 'getSelectedProduct']);
        Route::get('get/selected/product/variant/{productId}/{variantId}', [BarcodeController::class, 'getSelectedProductVariant']);
        Route::get('generate/product/barcode/{productId}', [BarcodeController::class, 'generateProductBarcode'])->name('products.generate.product.barcode');
        Route::get('get/specific/supplier/product/{productId}', [BarcodeController::class, 'getSpecificSupplierProduct'])->name('barcode.get.specific.supplier.product');

        // Generate bar-codes on purchase.
        Route::get('purchase/products/{purchaseId}', [BarcodeController::class, 'onPurchaseBarcode'])->name('barcode.on.purchase.barcode');
        Route::get('get/purchase/products/{purchaseId}', [BarcodeController::class, 'getPurchaseProduct'])->name('barcode.get.purchase.products');
    });

    // Import product route group
    Route::group(['prefix' => 'imports'], function () {
        Route::get('create', [ProductImportController::class, 'create'])->name('product.import.create');
        Route::post('store', [ProductImportController::class, 'store'])->name('product.import.store');
    });

    // Warranty route group
    Route::group(['prefix' => 'warranties'], function () {
        Route::get('/', [WarrantyController::class, 'index'])->name('product.warranties.index');
        Route::get('all', [WarrantyController::class, 'allWarranty'])->name('product.warranties.all.warranty');
        Route::post('store', [WarrantyController::class, 'store'])->name('product.warranties.store');
        Route::post('update', [WarrantyController::class, 'update'])->name('product.warranties.update');
        Route::delete('delete/{warrantyId}', [WarrantyController::class, 'delete'])->name('product.warranties.delete');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'stock'], function () {
            Route::get('/', [StockReportController::class, 'index'])->name('reports.stock.index');
            Route::get('print/branch/stocks', [StockReportController::class, 'printBranchStock'])->name('reports.stock.print.branch.stock');
            Route::get('warehouse/stock', [StockReportController::class, 'warehouseStock'])->name('reports.stock.warehouse.stock');
            Route::get('all/parent/categories', [StockReportController::class, 'allParentCategories'])->name('reports.stock.all.parent.categories');
        });

        Route::group(['prefix' => 'stock/in/out'], function () {
            Route::get('/', [StockInOutReportController::class, 'index'])->name('reports.stock.in.out.index');
            Route::get('print', [StockInOutReportController::class, 'print'])->name('reports.stock.in.out.print');
        });
    });
});

// Contact route group
Route::group(['prefix' => 'contacts'], function () {
    // Supplier route group
    Route::group(['prefix' => 'suppliers'], function () {

        Route::get('/', [SupplierController::class, 'index'])->name('contacts.supplier.index');
        Route::get('add', [SupplierController::class, 'create'])->name('contacts.supplier.create');
        Route::post('store', [SupplierController::class, 'store'])->name('contacts.supplier.store');
        Route::get('edit/{supplierId}', [SupplierController::class, 'edit'])->name('contacts.supplier.edit');
        Route::post('update', [SupplierController::class, 'update'])->name('contacts.supplier.update');
        Route::delete('delete/{supplierId}', [SupplierController::class, 'delete'])->name('contacts.supplier.delete');
        Route::get('change/status/{supplierId}', [SupplierController::class, 'changeStatus'])->name('contacts.supplier.change.status');
        Route::get('view/{supplierId}', [SupplierController::class, 'view'])->name('contacts.supplier.view');
        Route::get('uncompleted/orders/{supplierId}', [SupplierController::class, 'uncompletedOrders'])->name('suppliers.uncompleted.orders');
        Route::get('ledgers/{supplierId}', [SupplierController::class, 'ledgers'])->name('contacts.supplier.ledgers');
        Route::get('print/ledger/{supplierId}', [SupplierController::class, 'ledgerPrint'])->name('contacts.supplier.ledger.print');
        Route::get('all/payment/list/{supplierId}', [SupplierController::class, 'allPaymentList'])->name('suppliers.all.payment.list');
        Route::get('all/payment/print/{supplierId}', [SupplierController::class, 'allPaymentPrint'])->name('suppliers.all.payment.print');
        Route::get('payment/{supplierId}', [SupplierController::class, 'payment'])->name('suppliers.payment');
        Route::post('payment/{supplierId}', [SupplierController::class, 'paymentAdd'])->name('suppliers.payment.add');
        Route::get('return/payment/{supplierId}', [SupplierController::class, 'returnPayment'])->name('suppliers.return.payment');
        Route::post('return/payment/{supplierId}', [SupplierController::class, 'returnPaymentAdd'])->name('suppliers.return.payment.add');
        Route::get('payment/details/{paymentId}', [SupplierController::class, 'paymentDetails'])->name('suppliers.view.details');
        Route::delete('payment/delete/{paymentId}', [SupplierController::class, 'paymentDelete'])->name('suppliers.payment.delete');
        Route::get('amountsBranchWise/{supplierId}', [SupplierController::class, 'supplierAmountsBranchWise'])->name('contacts.supplier.amounts.branch.wise');

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', [SupplierImportController::class, 'create'])->name('contacts.suppliers.import.create');
            Route::post('store', [SupplierImportController::class, 'store'])->name('contacts.suppliers.import.store');
        });
    });

    // Customers route group
    Route::group(['prefix' => 'customers'], function () {

        Route::get('/', [CustomerController::class, 'index'])->name('contacts.customer.index');
        Route::get('add', [CustomerController::class, 'create'])->name('contacts.customer.create');
        Route::post('store', [CustomerController::class, 'store'])->name('contacts.customer.store');
        Route::post('addOpeningBalance', [CustomerController::class, 'addOpeningBalance'])->name('contacts.customer.add.opening.balance');
        Route::get('edit/{customerId}', [CustomerController::class, 'edit'])->name('contacts.customer.edit');
        Route::post('update', [CustomerController::class, 'update'])->name('contacts.customer.update');
        Route::delete('delete/{customerId}', [CustomerController::class, 'delete'])->name('contacts.customer.delete');
        Route::get('change/status/{customerId}', [CustomerController::class, 'changeStatus'])->name('contacts.customer.change.status');
        Route::get('view/{customerId}', [CustomerController::class, 'view'])->name('contacts.customer.view');
        Route::get('ledgers/list/{customerId}', [CustomerController::class, 'ledgerList'])->name('contacts.customer.ledger.list');
        Route::get('print/ledger/{customerId}', [CustomerController::class, 'ledgerPrint'])->name('contacts.customer.ledger.print');
        Route::get('payment/{customerId}', [CustomerController::class, 'payment'])->name('customers.payment');
        Route::post('payment/{customerId}', [CustomerController::class, 'paymentAdd'])->name('customers.payment.add');

        Route::get('return/payment/{customerId}', [CustomerController::class, 'returnPayment'])->name('customers.return.payment');
        Route::post('return/payment/{customerId}', [CustomerController::class, 'returnPaymentAdd'])->name('customers.return.payment.add');

        Route::get('all/payment/list/{customerId}', [CustomerController::class, 'allPaymentList'])->name('customers.all.payment.list');
        Route::get('all/payment/print/{customerId}', [CustomerController::class, 'allPaymentPrint'])->name('customers.all.payment.print');
        Route::get('payment/details/{paymentId}', [CustomerController::class, 'paymentDetails'])->name('customers.view.details');
        Route::delete('payment/delete/{paymentId}', [CustomerController::class, 'paymentDelete'])->name('customers.payment.delete');
        Route::get('amountsBranchWise/{customerId}', [CustomerController::class, 'customerAmountsBranchWise'])->name('contacts.customer.amounts.branch.wise');

        Route::group(['prefix' => 'money/receipt'], function () {
            Route::get('/voucher/list/{customerId}', [MoneyReceiptController::class, 'moneyReceiptList'])->name('money.receipt.voucher.list');
            Route::get('create/{customerId}', [MoneyReceiptController::class, 'moneyReceiptCreate'])->name('money.receipt.voucher.create');
            Route::post('store/{customerId}', [MoneyReceiptController::class, 'store'])->name('money.receipt.voucher.store');
            Route::get('edit/{receiptId}', [MoneyReceiptController::class, 'edit'])->name('money.receipt.voucher.edit');
            Route::post('update/{receiptId}', [MoneyReceiptController::class, 'update'])->name('money.receipt.voucher.update');
            Route::get('voucher/print/{receiptId}', [MoneyReceiptController::class, 'moneyReceiptPrint'])->name('money.receipt.voucher.print');
            Route::get('voucher/status/change/modal/{receiptId}', [MoneyReceiptController::class, 'changeStatusModal'])->name('money.receipt.voucher.status.change.modal');
            Route::post('voucher/status/change/{receiptId}', [MoneyReceiptController::class, 'changeStatus'])->name('money.receipt.voucher.status.change');
            Route::delete('voucher/delete/{receiptId}', [MoneyReceiptController::class, 'delete'])->name('money.receipt.voucher.delete');
        });

        Route::group(['prefix' => 'groups'], function () {
            Route::get('/', [CustomerGroupController::class, 'index'])->name('contacts.customers.groups.index');
            Route::get('all/groups', [CustomerGroupController::class, 'allBanks'])->name('contacts.customers.groups.all.group');
            Route::post('store', [CustomerGroupController::class, 'store'])->name('contacts.customers.groups.store');
            Route::post('update', [CustomerGroupController::class, 'update'])->name('contacts.customers.groups.update');
            Route::delete('delete/{groupId}', [CustomerGroupController::class, 'delete'])->name('customers.groups.delete');
        });

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', [CustomerImportController::class, 'create'])->name('contacts.customers.import.create');
            Route::post('store', [CustomerImportController::class, 'store'])->name('contacts.customers.import.store');
        });
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

    Route::get('v2', [PurchaseController::class, 'index_v2'])->name('purchases.index_v2');
    Route::get('product/list', [PurchaseController::class, 'purchaseProductList'])->name('purchases.product.list');
    Route::get('po/list', [PurchaseController::class, 'poList'])->name('purchases.po.list');
    Route::get('show/{purchaseId}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('order/show/{purchaseId}', [PurchaseController::class, 'showOrder'])->name('purchases.show.order');
    Route::get('order/print/supplier/copy/{purchaseId}', [PurchaseController::class, 'printSupplierCopy'])->name('purchases.order.supplier.copy.print');
    Route::get('create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('store', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('edit/{purchaseId}/{editType}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('editable/purchase/{purchaseId}/{editType}', [PurchaseController::class, 'editablePurchase'])->name('purchases.get.editable.purchase');
    Route::post('update/{editType}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::get('get/all/supplier', [PurchaseController::class, 'getAllSupplier'])->name('purchases.get.all.supplier');
    Route::get('get/all/unit', [PurchaseController::class, 'getAllUnit'])->name('purchases.get.all.unites');
    Route::get('get/all/tax', [PurchaseController::class, 'getAllTax'])->name('purchases.get.all.taxes');
    Route::get('search/product/{product_code}', [PurchaseController::class, 'searchProduct']);
    Route::delete('delete/{purchaseId}', [PurchaseController::class, 'delete'])->name('purchase.delete');
    Route::post('add/supplier', [PurchaseController::class, 'addSupplier'])->name('purchases.add.supplier');
    Route::get('add/product/modal/view', [PurchaseController::class, 'addProductModalView'])->name('purchases.add.product.modal.view');
    Route::post('add/product', [PurchaseController::class, 'addProduct'])->name('purchases.add.product');
    Route::get('recent/product/{productId}', [PurchaseController::class, 'getRecentProduct']);
    Route::get('add/quick/supplier/modal', [PurchaseController::class, 'addQuickSupplierModal'])->name('purchases.add.quick.supplier.modal');
    Route::get('payment/modal/{purchaseId}', [PurchaseController::class, 'paymentModal'])->name('purchases.payment.modal');
    Route::post('payment/store/{purchaseId}', [PurchaseController::class, 'paymentStore'])->name('purchases.payment.store');
    Route::get('payment/edit/{paymentId}', [PurchaseController::class, 'paymentEdit'])->name('purchases.payment.edit');
    Route::post('payment/update/{paymentId}', [PurchaseController::class, 'paymentUpdate'])->name('purchases.payment.update');
    Route::get('return/payment/modal/{purchaseId}', [PurchaseController::class, 'returnPaymentModal'])->name('purchases.return.payment.modal');
    Route::post('return/payment/store/{purchaseId}', [PurchaseController::class, 'returnPaymentStore'])->name('purchases.return.payment.store');
    Route::get('return/payment/edit/{paymentId}', [PurchaseController::class, 'returnPaymentEdit'])->name('purchases.return.payment.edit');
    Route::post('return/payment/update/{paymentId}', [PurchaseController::class, 'returnPaymentUpdate'])->name('purchases.return.payment.update');
    Route::get('payment/details/{paymentId}', [PurchaseController::class, 'paymentDetails'])->name('purchases.payment.details');
    Route::delete('payment/delete/{paymentId}', [PurchaseController::class, 'paymentDelete'])->name('purchases.payment.delete');
    Route::get('payment/list/{purchaseId}', [PurchaseController::class, 'paymentList'])->name('purchase.payment.list');
    Route::get('settings', [PurchaseController::class, 'settings'])->name('purchase.settings');
    Route::post('settings/store', [PurchaseController::class, 'settingsStore'])->name('purchase.settings.store');

    Route::group(['prefix' => '/'], function () {
        Route::get('po/process/receive/{purchaseId}', [PurchaseOrderReceiveController::class, 'processReceive'])->name('purchases.po.receive.process');
        Route::post('po/process/receive/store/{purchaseId}', [PurchaseOrderReceiveController::class, 'processReceiveStore'])->name('purchases.po.receive.process.store');
    });

    // Purchase Return route
    Route::group(['prefix' => 'returns'], function () {
        Route::get('/', [PurchaseReturnController::class, 'index'])->name('purchases.returns.index');
        Route::get('show/{returnId}', [PurchaseReturnController::class, 'show'])->name('purchases.returns.show');
        Route::get('add/{purchaseId}', [PurchaseReturnController::class, 'create'])->name('purchases.returns.create');
        Route::get('get/purchase/{purchaseId}', [PurchaseReturnController::class, 'getPurchase'])->name('purchases.returns.get.purchase');
        Route::post('store/{purchaseId}', [PurchaseReturnController::class, 'store'])->name('purchases.returns.store');
        Route::delete('delete/{purchaseReturnId}', [PurchaseReturnController::class, 'delete'])->name('purchases.returns.delete');
        Route::get('create', [PurchaseReturnController::class, 'supplierReturn'])->name('purchases.returns.supplier.return');
        Route::get('search/product/{productCode}/{warehouseId}', [PurchaseReturnController::class, 'searchProduct']);
        Route::get('check/single/product/stock/{product_id}/{warehouse_id}', [PurchaseReturnController::class, 'checkSingleProductStock']);
        Route::get('check/variant/product/stock/{product_id}/{variant_id}/{warehouse_id}', [PurchaseReturnController::class, 'checkVariantProductStock']);
        Route::post('supplier/return/store', [PurchaseReturnController::class, 'supplierReturnStore'])->name('purchases.returns.supplier.return.store');
        Route::get('supplier/return/edit/{purchaseReturnId}', [PurchaseReturnController::class, 'supplierReturnEdit'])->name('purchases.returns.supplier.return.edit');
        Route::get('get/editable/supplierReturn/{purchaseReturnId}', [PurchaseReturnController::class, 'getEditableSupplierReturn'])->name('purchases.return.get.editable.supplier.return');
        Route::post('supplier/return/update/{purchaseReturnId}', [PurchaseReturnController::class, 'supplierReturnUpdate'])->name('purchases.returns.supplier.return.update');
        Route::post('return/payments/{returnId}', [PurchaseReturnController::class, 'returnPaymentList'])->name('purchases.returns.purchase.return.payment.list');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'purchase/statements'], function () {
            Route::get('/', [PurchaseStatementController::class, 'index'])->name('reports.purchases.statement.index');
            Route::get('print', [PurchaseStatementController::class, 'print'])->name('reports.purchases.statement.print');
        });

        Route::group(['prefix' => 'product/purchases'], function () {
            Route::get('/', [ProductPurchaseReportController::class, 'index'])->name('reports.product.purchases.index');
            Route::get('print', [ProductPurchaseReportController::class, 'print'])->name('reports.product.purchases.print');
        });

        Route::group(['prefix' => 'purchase/payments'], function () {
            Route::get('/', [PurchasePaymentReportController::class, 'index'])->name('reports.purchase.payments.index');
            Route::get('print', [PurchasePaymentReportController::class, 'print'])->name('reports.purchase.payments.print');
        });

        Route::group(['prefix' => 'sales/purchase'], function () {
            Route::get('/', [SalePurchaseReportController::class, 'index'])->name('reports.sales.purchases.index');
            Route::get('sale/purchase/amounts', [SalePurchaseReportController::class, 'salePurchaseAmounts'])->name('reports.profit.sales.purchases.amounts');
            Route::get('filter/sale/purchase/amounts', [SalePurchaseReportController::class, 'filterSalePurchaseAmounts'])->name('reports.profit.sales.filter.purchases.amounts');
            Route::get('print', [SalePurchaseReportController::class, 'printSalePurchase'])->name('reports.sales.purchases.print');
        });
    });
});

// Sale route group sales/recent/sales
Route::group(['prefix' => 'sales'], function () {

    Route::get('v2', [SaleController::class, 'index2'])->name('sales.index2');
    Route::get('pos/list', [SaleController::class, 'posList'])->name('sales.pos.list');
    Route::get('product/list', [SaleController::class, 'soldProductList'])->name('sales.product.list');
    Route::get('show/{saleId}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('pos/show/{saleId}', [SaleController::class, 'posShow'])->name('sales.pos.show');
    Route::get('print/{saleId}', [SaleController::class, 'print'])->name('sales.print');
    Route::get('packing/Slip/{saleId}', [SaleController::class, 'packingSlip'])->name('sales.packing.slip');
    Route::get('drafts', [SaleController::class, 'drafts'])->name('sales.drafts');
    Route::get('draft/details/{draftId}', [SaleController::class, 'draftDetails'])->name('sales.drafts.details');
    Route::get('sales/order/list', [SaleController::class, 'salesOrderList'])->name('sales.order.list');
    Route::get('quotations', [SaleController::class, 'quotations'])->name('sales.quotations');
    Route::get('quotation/details/{quotationId}', [SaleController::class, 'quotationDetails'])->name('sales.quotations.details');
    Route::get('create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('edit/{saleId}', [SaleController::class, 'edit'])->name('sales.edit');
    Route::post('update/{saleId}', [SaleController::class, 'update'])->name('sales.update');
    Route::get('get/all/customer', [SaleController::class, 'getAllCustomer'])->name('sales.get.all.customer');
    Route::get('get/all/users', [SaleController::class, 'getAllUser'])->name('sales.get.all.users');
    Route::get('get/all/unit', [SaleController::class, 'getAllUnit'])->name('sales.get.all.unites');
    Route::get('get/all/tax', [SaleController::class, 'getAllTax'])->name('sales.get.all.taxes');
    Route::get('search/product/{status}/{product_code}/{price_group_id}/{warehouse_id}', [SaleController::class, 'searchProduct']);
    Route::delete('delete/{saleId}', [SaleController::class, 'delete'])->name('sales.delete');
    Route::get('edit/shipment/{saleId}', [SaleController::class, 'editShipment'])->name('sales.shipment.edit');
    Route::post('update/shipment/{saleId}', [SaleController::class, 'updateShipment'])->name('sales.shipment.update');
    Route::post('change/status/{saleId}', [SaleController::class, 'changeStatus'])->name('sales.change.status');
    Route::get('check/branch/variant/qty/{status}/{product_id}/{variant_id}/{price_group_id}/{warehouse_id}', [SaleController::class, 'checkVariantProductStock']);
    Route::get('check/single/product/stock/{status}/{product_id}/{price_group_id}/{warehouse_id}', [SaleController::class, 'checkSingleProductStock']);

    Route::get('shipments', [SaleController::class, 'shipments'])->name('sales.shipments');

    // Sale payment route
    Route::get('payment/{saleId}', [SaleController::class, 'paymentModal'])->name('sales.payment.modal');
    Route::post('payment/add/{saleId}', [SaleController::class, 'paymentAdd'])->name('sales.payment.add');

    Route::get('payment/view/{saleId}', [SaleController::class, 'viewPayment'])->name('sales.payment.view');
    Route::get('payment/edit/{paymentId}', [SaleController::class, 'paymentEdit'])->name('sales.payment.edit');
    Route::post('payment/update/{paymentId}', [SaleController::class, 'paymentUpdate'])->name('sales.payment.update');
    Route::get('payment/details/{paymentId}', [SaleController::class, 'paymentDetails'])->name('sales.payment.details');

    Route::delete('payment/delete/{paymentId}', [SaleController::class, 'paymentDelete'])->name('sales.payment.delete');

    Route::get('return/payment/{saleId}', [SaleController::class, 'returnPaymentModal'])->name('sales.return.payment.modal');
    Route::post('return/payment/add/{saleId}', [SaleController::class, 'returnPaymentAdd'])->name('sales.return.payment.add');
    Route::get('return/payment/edit/{paymentId}', [SaleController::class, 'returnPaymentEdit'])->name('sales.return.payment.edit');
    Route::post('return/payment/update/{paymentId}', [SaleController::class, 'returnPaymentUpdate'])->name('sales.return.payment.update');

    Route::get('add/product/modal/view', [SaleController::class, 'addProductModalView'])->name('sales.add.product.modal.view');
    Route::post('add/product', [SaleController::class, 'addProduct'])->name('sales.add.product');
    Route::get('get/recent/product/{product_id}', [SaleController::class, 'getRecentProduct']);
    Route::get('get/product/price/group', [SaleController::class, 'getProductPriceGroup'])->name('sales.product.price.groups');

    Route::get('notification/form/{saleId}', [SaleController::class, 'getNotificationForm'])->name('sales.notification.form');

    Route::get('settings', [SaleController::class, 'settings'])->name('sales.add.sale.settings');
    Route::post('settings/store', [SaleController::class, 'settingsStore'])->name('sales.add.sale.settings.store');

    // Sale return route
    Route::group(['prefix' => 'returns'], function () {

        Route::get('/', [SaleReturnController::class, 'index'])->name('sales.returns.index');
        Route::get('show/{returnId}', [SaleReturnController::class, 'show'])->name('sales.returns.show');

        Route::delete('delete/{saleReturnId}', [SaleReturnController::class, 'delete'])->name('sales.returns.delete');
        Route::get('payment/list/{returnId}', [SaleReturnController::class, 'returnPaymentList'])->name('sales.returns.payment.list');

        Route::group(['prefix' => 'random'], function () {

            Route::get('create', [RandomSaleReturnController::class, 'create'])->name('sale.return.random.create');
            Route::post('store', [RandomSaleReturnController::class, 'store'])->name('sale.return.random.store');
            Route::get('edit/{returnId}', [RandomSaleReturnController::class, 'edit'])->name('sale.return.random.edit');
            Route::post('update/{returnId}', [RandomSaleReturnController::class, 'update'])->name('sale.return.random.update');
            Route::get('search/product/{product_code}', [RandomSaleReturnController::class, 'searchProduct']);
        });
    });

    //Pos cash register routes
    Route::group(['prefix' => 'cash/register'], function () {

        Route::get('/', [CashRegisterController::class, 'create'])->name('sales.cash.register.create');
        Route::post('store', [CashRegisterController::class, 'store'])->name('sales.cash.register.store');
        Route::get('close/cash/register/modal/view', [CashRegisterController::class, 'closeCashRegisterModalView'])->name('sales.cash.register.close.modal.view');
        Route::get('cash/register/details', [CashRegisterController::class, 'cashRegisterDetails'])->name('sales.cash.register.details');
        Route::get('cash/register/details/for/report/{crId}', [CashRegisterController::class, 'cashRegisterDetailsForReport'])->name('sales.cash.register.details.for.report');
        Route::post('close', [CashRegisterController::class, 'close'])->name('sales.cash.register.close');
    });

    // Pos routes
    Route::group(['prefix' => 'pos'], function () {

        Route::get('create', [POSController::class, 'create'])->name('sales.pos.create');
        Route::get('product/list', [POSController::class, 'posProductList'])->name('sales.pos.product.list');
        Route::post('store', [POSController::class, 'store'])->name('sales.pos.store');
        Route::get('pick/hold/invoice', [POSController::class, 'pickHoldInvoice']);
        Route::get('edit/{saleId}', [POSController::class, 'edit'])->name('sales.pos.edit');
        Route::get('invoice/products/{saleId}', [POSController::class, 'invoiceProducts'])->name('sales.pos.invoice.products');
        Route::post('update', [POSController::class, 'update'])->name('sales.pos.update');
        Route::get('suspended/sale/list', [POSController::class, 'suspendedList'])->name('sales.pos.suspended.list');
        Route::get('branch/stock', [POSController::class, 'branchStock'])->name('sales.pos.branch.stock');
        Route::get('add/customer/modal', [POSController::class, 'addQuickCustomerModal'])->name('sales.pos.add.quick.customer.modal');
        Route::post('add/customer', [POSController::class, 'addCustomer'])->name('sales.pos.add.customer');
        Route::get('get/recent/product/{product_id}', [POSController::class, 'getRecentProduct']);
        Route::get('search/exchangeable/invoice', [POSController::class, 'searchExchangeableInv'])->name('sales.pos.search.exchange.invoice');
        Route::post('prepare/exchange', [POSController::class, 'prepareExchange'])->name('sales.pos.prepare.exchange');
        Route::post('exchange/confirm', [POSController::class, 'exchangeConfirm'])->name('sales.pos.exchange.confirm');
        Route::get('settings', [POSController::class, 'settings'])->name('sales.pos.settings');
        Route::post('settings/store', [POSController::class, 'settingsStore'])->name('sales.pos.settings.store');
    });

    //Sale discount routes
    Route::group(['prefix' => 'discounts'], function () {

        Route::get('/', [DiscountController::class, 'index'])->name('sales.discounts.index');
        Route::post('store', [DiscountController::class, 'store'])->name('sales.discounts.store');
        Route::get('edit/{discountId}', [DiscountController::class, 'edit'])->name('sales.discounts.edit');
        Route::post('update/{discountId}', [DiscountController::class, 'update'])->name('sales.discounts.update');
        Route::get('change/status/{discountId}', [DiscountController::class, 'changeStatus'])->name('sales.discounts.change.status');
        Route::delete('delete/{discountId}', [DiscountController::class, 'delete'])->name('sales.discounts.delete');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::group(['prefix' => 'sold/products'], function () {

            Route::get('/', [ProductSaleReportController::class, 'index'])->name('reports.product.sales.index');
            Route::get('print', [ProductSaleReportController::class, 'print'])->name('reports.product.sales.print');
        });

        Route::group(['prefix' => 'received/payments'], function () {

            Route::get('/', [SalePaymentReportController::class, 'index'])->name('reports.sale.payments.index');
            Route::get('print', [SalePaymentReportController::class, 'print'])->name('reports.sale.payments.print');
        });

        Route::group(['prefix' => 'cash/registers'], function () {

            Route::get('/', [CashRegisterReportController::class, 'index'])->name('reports.cash.registers.index');
            Route::get('get', [CashRegisterReportController::class, 'getCashRegisterReport'])->name('reports.get.cash.registers');
            Route::get('details/{cashRegisterId}', [CashRegisterReportController::class, 'detailsCashRegister'])->name('reports.get.cash.register.details');
            Route::get('report/print', [CashRegisterReportController::class, 'reportPrint'])->name('reports.get.cash.register.report.print');
        });

        Route::group(['prefix' => 'sale/representative'], function () {

            Route::get('/', [SaleRepresentativeReportController::class, 'index'])->name('reports.sale.representative.index');
            Route::get('expenses', [SaleRepresentativeReportController::class, 'SaleRepresentativeExpenseReport'])->name('reports.sale.representative.expenses');
        });

        Route::group(['prefix' => 'sale/statements'], function () {

            Route::get('/', [SaleStatementController::class, 'index'])->name('reports.sale.statement.index');
            Route::get('print', [SaleStatementController::class, 'print'])->name('reports.sale.statement.print');
        });

        Route::group(['prefix' => 'return/statements'], function () {

            Route::get('/', [SaleReturnStatementController::class, 'index'])->name('reports.sale.return.statement.index');
            Route::get('print', [SaleReturnStatementController::class, 'print'])->name('reports.sale.return.statement.print');
        });
    });
});

//Transfer stock to branch all route
Route::group(['prefix' => 'transfer/stocks'], function () {

    Route::get('/', [TransferToBranchController::class, 'index'])->name('transfer.stock.to.branch.index');
    Route::get('show/{transferId}', [TransferToBranchController::class, 'show'])->name('transfer.stock.to.branch.show');
    Route::get('transfer/products/{transferId}', [TransferToBranchController::class, 'transferProduct']);
    Route::get('all/transfer/', [TransferToBranchController::class, 'allTransfer'])->name('transfer.stock.to.branch.all.transfer');
    Route::get('create', [TransferToBranchController::class, 'create'])->name('transfer.stock.to.branch.create');
    Route::post('store', [TransferToBranchController::class, 'store'])->name('transfer.stock.to.branch.store');
    Route::get('get/all/warehouse', [TransferToBranchController::class, 'getAllWarehouse'])->name('transfer.stock.to.branch.all.warehouse');
    Route::get('edit/{transferId}', [TransferToBranchController::class, 'edit'])->name('transfer.stock.to.branch.edit');
    Route::get('get/editable/transfer/{transferId}', [TransferToBranchController::class, 'editableTransfer'])->name('transfer.stock.to.branch.editable.transfer');
    Route::post('update/{transferId}', [TransferToBranchController::class, 'update'])->name('transfer.stock.to.branch.update');
    Route::delete('delete/{transferId}', [TransferToBranchController::class, 'delete'])->name('transfer.stock.to.branch.delete');
    Route::get('search/product/{product_code}/{warehouse_id}', [TransferToBranchController::class, 'productSearch']);
    Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseProductVariant']);
    Route::get('check/warehouse/qty/{product_id}/{warehouse_id}', [TransferToBranchController::class, 'checkWarehouseSingleProduct']);

    // Receive stock from warehouse **route group**
    Route::group(['prefix' => 'receive'], function () {
        Route::get('/', [WarehouseReceiveStockController::class, 'index'])->name('transfer.stocks.to.branch.receive.stock.index');
        Route::get('show/{sendStockId}', [WarehouseReceiveStockController::class, 'show'])->name('transfer.stocks.to.branch.receive.stock.show');
        Route::get('process/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.branch.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', [WarehouseReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.branch.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', [WarehouseReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.branch.receive.stock.process.save');
    });

    //Transfer Stock Branch To Branch
    Route::group(['prefix' => 'branch/to/branch'], function () {

        Route::get('transfer/list', [TransferStockBranchToBranchController::class, 'transferList'])->name('transfer.stock.branch.to.branch.transfer.list');

        Route::get('create', [TransferStockBranchToBranchController::class, 'create'])->name('transfer.stock.branch.to.branch.create');

        Route::get('show/{transferId}', [TransferStockBranchToBranchController::class, 'show'])->name('transfer.stock.branch.to.branch.show');

        Route::post('store', [TransferStockBranchToBranchController::class, 'store'])->name('transfer.stock.branch.to.branch.store');

        Route::get('edit/{transferId}', [TransferStockBranchToBranchController::class, 'edit'])->name('transfer.stock.branch.to.branch.edit');

        Route::post('update/{transferId}', [TransferStockBranchToBranchController::class, 'update'])->name('transfer.stock.branch.to.branch.update');

        Route::delete('delete/{transferId}', [TransferStockBranchToBranchController::class, 'delete'])->name('transfer.stock.branch.to.branch.delete');

        Route::get('search/product/{product_code}/{warehouse_id}/{receiver_branch_id?}', [TransferStockBranchToBranchController::class, 'searchProduct']);

        Route::get('check/single/product/stock/{product_id}/{warehouse_id}/{receiver_branch_id?}', [TransferStockBranchToBranchController::class, 'checkSingleProductStock']);

        Route::get('check/variant/product/stock/{product_id}/{variant_id}/{warehouse_id}/{receiver_branch_id?}', [TransferStockBranchToBranchController::class, 'checkVariantProductStock']);

        Route::group(['prefix' => 'receive'], function () {

            Route::get('receivable/list', [ReceiveTransferBranchToBranchController::class, 'receivableList'])->name('transfer.stock.branch.to.branch.receivable.list');

            Route::get('show/{transferId}', [ReceiveTransferBranchToBranchController::class, 'show'])->name('transfer.stock.branch.to.branch.receivable.show');

            Route::get('process/to/receive/{transferId}', [ReceiveTransferBranchToBranchController::class, 'processToReceive'])->name('transfer.stock.branch.to.branch.ProcessToReceive');

            Route::post('process/to/receive/save/{transferId}', [ReceiveTransferBranchToBranchController::class, 'processToReceiveSave'])->name('transfer.stock.branch.to.branch.ProcessToReceive.save');
        });
    });
});

//Stock adjustment to branch all route
Route::group(['prefix' => 'stock/adjustments'], function () {

    Route::get('/', [StockAdjustmentController::class, 'index'])->name('stock.adjustments.index');
    Route::get('show/{adjustmentId}', [StockAdjustmentController::class, 'show'])->name('stock.adjustments.show');
    Route::get('create', [StockAdjustmentController::class, 'create'])->name('stock.adjustments.create');
    Route::get('create/from/warehouse', [StockAdjustmentController::class, 'createFromWarehouse'])->name('stock.adjustments.create.from.warehouse');
    Route::post('store', [StockAdjustmentController::class, 'store'])->name('stock.adjustments.store');
    Route::get('search/product/in/warehouse/{keyword}/{warehouse_id}', [StockAdjustmentController::class, 'searchProductInWarehouse']);
    Route::get('search/product/{keyword}', [StockAdjustmentController::class, 'searchProduct']);

    Route::get('check/single/product/stock/{product_id}', [StockAdjustmentController::class, 'checkSingleProductStock']);
    Route::get('check/single/product/stock/in/warehouse/{product_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkSingleProductStockInWarehouse']);

    Route::get('check/variant/product/stock/{product_id}/{variant_id}', [StockAdjustmentController::class, 'checkVariantProductStock']);
    Route::get('check/variant/product/stock/in/warehouse/{product_id}/{variant_id}/{warehouse_id}', [StockAdjustmentController::class, 'checkVariantProductStockInWarehouse']);
    Route::delete('delete/{adjustmentId}', [StockAdjustmentController::class, 'delete'])->name('stock.adjustments.delete');

    Route::group(['prefix' => 'reports/stock/adjustments'], function () {

        Route::get('/', [StockAdjustmentReportController::class, 'index'])->name('reports.stock.adjustments.index');
        Route::get('all/adjustments', [StockAdjustmentReportController::class, 'allAdjustments'])->name('reports.stock.adjustments.all');
        Route::get('print', [StockAdjustmentReportController::class, 'print'])->name('reports.stock.adjustments.print');
    });
});

//Transfer stock to warehouse all route
Route::group(['prefix' => 'transfer/stocks/to/warehouse'], function () {

    Route::get('/', [TransferToWarehouseController::class, 'index'])->name('transfer.stock.to.warehouse.index');
    Route::get('show/{id}', [TransferToWarehouseController::class, 'show'])->name('transfer.stock.to.warehouse.show');
    Route::get('create', [TransferToWarehouseController::class, 'create'])->name('transfer.stock.to.warehouse.create');
    Route::post('store', [TransferToWarehouseController::class, 'store'])->name('transfer.stock.to.warehouse.store');
    Route::get('get/all/warehouse', [TransferToWarehouseController::class, 'getAllWarehouse'])->name('transfer.stock.to.warehouse.all.warehouse');
    Route::get('edit/{transferId}', [TransferToWarehouseController::class, 'edit'])->name('transfer.stock.to.warehouse.edit');
    Route::get('get/editable/transfer/{transferId}', [TransferToWarehouseController::class, 'editableTransfer'])->name('transfer.stock.to.warehouse.editable.transfer');
    Route::post('update/{transferId}', [TransferToWarehouseController::class, 'update'])->name('transfer.stock.to.warehouse.update');
    Route::delete('delete/{transferId}', [TransferToWarehouseController::class, 'delete'])->name('transfer.stock.to.warehouse.delete');
    Route::get('search/product/{product_code}', [TransferToWarehouseController::class, 'productSearch']);
    Route::get('check/single/product/stock/{product_id}', [TransferToWarehouseController::class, 'checkBranchSingleProduct']);
    Route::get('check/branch/variant/qty/{product_id}/{variant_id}', [TransferToWarehouseController::class, 'checkBranchProductVariant']);

    // Receive stock from branch **route group**
    Route::group(['prefix' => 'receive'], function () {

        Route::get('/', [BranchReceiveStockController::class, 'index'])->name('transfer.stocks.to.warehouse.receive.stock.index');
        Route::get('show/{sendStockId}', [BranchReceiveStockController::class, 'show'])->name('transfer.stocks.to.warehouse.receive.stock.show');
        Route::get('all/send/stocks', [BranchReceiveStockController::class, 'allSendStock'])->name('transfer.stocks.to.warehouse.receive.stock.all.send.stocks');
        Route::get('process/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessView'])->name('transfer.stocks.to.warehouse.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', [BranchReceiveStockController::class, 'receivableStock'])->name('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', [BranchReceiveStockController::class, 'receiveProcessSave'])->name('transfer.stocks.to.warehouse.receive.stock.process.save');
        Route::post('mail/{sendStockId}', [BranchReceiveStockController::class, 'receiveMail'])->name('transfer.stocks.to.warehouse.receive.stock.mail');
    });
});

// Expense route group
Route::group(['prefix' => 'expenses'], function () {

    Route::get('/', [ExpanseController::class, 'index'])->name('expanses.index');
    Route::get('category/wise/expenses', [ExpanseController::class, 'categoryWiseExpense'])->name('expanses.category.wise.expense');
    Route::get('create', [ExpanseController::class, 'create'])->name('expanses.create');
    Route::post('store', [ExpanseController::class, 'store'])->name('expanses.store');
    Route::get('edit/{expanseId}', [ExpanseController::class, 'edit'])->name('expanses.edit');
    Route::post('update/{expenseId}', [ExpanseController::class, 'update'])->name('expanses.update');
    Route::delete('delete/{expanseId}', [ExpanseController::class, 'delete'])->name('expanses.delete');
    Route::get('all/categories', [ExpanseController::class, 'allCategories'])->name('expanses.all.categories');
    Route::get('payment/modal/{expenseId}', [ExpanseController::class, 'paymentModal'])->name('expanses.payment.modal');
    Route::post('payment/{expenseId}', [ExpanseController::class, 'payment'])->name('expanses.payment');
    Route::get('payment/view/{expenseId}', [ExpanseController::class, 'paymentView'])->name('expanses.payment.view');
    Route::get('payment/details/{paymentId}', [ExpanseController::class, 'paymentDetails'])->name('expanses.payment.details');
    Route::get('payment/edit/{paymentId}', [ExpanseController::class, 'paymentEdit'])->name('expanses.payment.edit');
    Route::post('payment/update/{paymentId}', [ExpanseController::class, 'paymentUpdate'])->name('expanses.payment.update');
    Route::delete('payment/delete/{paymentId}', [ExpanseController::class, 'paymentDelete'])->name('expanses.payment.delete');
    Route::post('add/quick/expense/category', [ExpanseController::class, 'addQuickExpenseCategory'])->name('expanses.add.quick.expense.category');

    // Expanse category route group
    Route::group(['prefix' => 'categories'], function () {

        Route::get('/', [ExpanseCategoryController::class, 'index'])->name('expanses.categories.index');
        Route::get('all/categories', [ExpanseCategoryController::class, 'allCategory'])->name('expanses.categories.all.category');
        Route::post('store', [ExpanseCategoryController::class, 'store'])->name('expanses.categories.store');
        Route::post('update', [ExpanseCategoryController::class, 'update'])->name('expanses.categories.update');
        Route::delete('delete/{categoryId}', [ExpanseCategoryController::class, 'delete'])->name('expanses.categories.delete');
    });

    Route::group(['prefix' => 'report/expenses'], function () {

        Route::get('/', [ExpanseReportController::class, 'index'])->name('reports.expenses.index');
        Route::get('print', [ExpanseReportController::class, 'print'])->name('reports.expenses.print');
    });
});

Route::group(['prefix' => 'accounting'], function () {

    Route::group(['prefix' => 'banks'], function () {

        Route::get('/', [BankController::class, 'index'])->name('accounting.banks.index');
        Route::get('all/banks', [BankController::class, 'allBanks'])->name('accounting.banks.all.bank');
        Route::post('store', [BankController::class, 'store'])->name('accounting.banks.store');
        Route::post('update', [BankController::class, 'update'])->name('accounting.banks.update');
        Route::delete('delete/{bankId}', [BankController::class, 'delete'])->name('accounting.banks.delete');
    });

    Route::group(['prefix' => 'accounts'], function () {

        Route::get('/', [AccountController::class, 'index'])->name('accounting.accounts.index');
        Route::get('account/book/{accountId}', [AccountController::class, 'accountBook'])->name('accounting.accounts.book');
        Route::get('account/ledger/print/{accountId}', [AccountController::class, 'ledgerPrint'])->name('accounting.accounts.ledger.print');
        Route::post('store', [AccountController::class, 'store'])->name('accounting.accounts.store');
        Route::get('edit/{id}', [AccountController::class, 'edit'])->name('accounting.accounts.edit');
        Route::post('update/{id}', [AccountController::class, 'update'])->name('accounting.accounts.update');
        Route::delete('delete/{accountId}', [AccountController::class, 'delete'])->name('accounting.accounts.delete');
    });

    Route::group(['prefix' => 'contras'], function () {

        Route::get('/', [ContraController::class, 'index'])->name('accounting.contras.index');
        Route::get('create', [ContraController::class, 'create'])->name('accounting.contras.create');
        Route::get('show/{contraId}', [ContraController::class, 'show'])->name('accounting.contras.show');
        Route::get('account/book/{contraId}', [ContraController::class, 'accountBook'])->name('accounting.contras.book');
        Route::post('store', [ContraController::class, 'store'])->name('accounting.contras.store');
        Route::get('edit/{contraId}', [ContraController::class, 'edit'])->name('accounting.contras.edit');
        Route::post('update/{contraId}', [ContraController::class, 'update'])->name('accounting.contras.update');
        Route::delete('delete/{contraId}', [ContraController::class, 'delete'])->name('accounting.contras.delete');
    });

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

        Route::group(['prefix' => 'daily/profit/loss'], function () {

            Route::get('/', [ProfitLossReportController::class, 'index'])->name('reports.profit.loss.index');
            Route::get('sale/purchase/profit', [ProfitLossReportController::class, 'salePurchaseProfit'])->name('reports.profit.sale.purchase.profit');
            Route::get('filter/sale/purchase/profit/filter', [ProfitLossReportController::class, 'filterSalePurchaseProfit'])->name('reports.profit.filter.sale.purchase.profit');
            Route::get('print', [ProfitLossReportController::class, 'printProfitLoss'])->name('reports.profit.loss.print');
        });

        Route::group(['prefix' => 'financial'], function () {

            Route::get('/', [FinancialReportControllerReport::class, 'index'])->name('reports.financial.index');
            Route::get('amounts', [FinancialReportControllerReport::class, 'financialAmounts'])->name('reports.financial.amounts');
            Route::get('filter/amounts', [FinancialReportControllerReport::class, 'filterFinancialAmounts'])->name('reports.financial.filter.amounts');
            Route::get('report/print', [FinancialReportControllerReport::class, 'print'])->name('reports.financial.report.print');
        });
    });
});

Route::group(['prefix' => 'settings'], function () {

    Route::group(['prefix' => 'branches'], function () {

        Route::get('/', [BranchController::class, 'index'])->name('settings.branches.index');
        Route::get('get/all/branch', [BranchController::class, 'getAllBranch'])->name('settings.get.all.branch');
        Route::get('create', [BranchController::class, 'create'])->name('settings.branches.create');
        Route::post('store', [BranchController::class, 'store'])->name('settings.branches.store');
        Route::get('edit/{branchId}', [BranchController::class, 'edit'])->name('settings.branches.edit');
        Route::post('update/{branchId}', [BranchController::class, 'update'])->name('settings.branches.update');
        Route::delete('delete/{id}', [BranchController::class, 'delete'])->name('settings.branches.delete');
        Route::get('quick/invoice/schema/modal', [BranchController::class, 'quickInvoiceSchemaModal'])->name('settings.branches.quick.invoice.schema.modal');
        Route::post('quick/invoice/schema/store', [BranchController::class, 'quickInvoiceSchemaStore'])->name('settings.branches.quick.invoice.schema.store');
    });

    Route::group(['prefix' => 'warehouses'], function () {

        Route::get('/', [WarehouseController::class, 'index'])->name('settings.warehouses.index');
        Route::post('store', [WarehouseController::class, 'store'])->name('settings.warehouses.store');
        Route::get('edit/{id}', [WarehouseController::class, 'edit'])->name('settings.warehouses.edit');
        Route::post('update/{id}', [WarehouseController::class, 'update'])->name('settings.warehouses.update');
        Route::delete('delete/{warehouseId}', [WarehouseController::class, 'delete'])->name('settings.warehouses.delete');
    });

    Route::group(['prefix' => 'units'], function () {

        Route::get('/', [UnitController::class, 'index'])->name('settings.units.index');
        Route::get('get/all/unit', [UnitController::class, 'getAllUnit'])->name('settings.units.get.all.unit');
        Route::post('store', [UnitController::class, 'store'])->name('settings.units.store');
        Route::post('update', [UnitController::class, 'update'])->name('settings.units.update');
        Route::delete('delete/{unitId}', [UnitController::class, 'delete'])->name('settings.units.delete');
    });

    Route::group(['prefix' => 'taxes'], function () {

        Route::get('/', [TaxController::class, 'index'])->name('settings.taxes.index');
        Route::get('get/all/vat', [TaxController::class, 'getAllVat'])->name('settings.taxes.get.all.tax');
        Route::post('store', [TaxController::class, 'store'])->name('settings.taxes.store');
        Route::post('update', [TaxController::class, 'update'])->name('settings.taxes.update');
        Route::delete('delete/{taxId}', [TaxController::class, 'delete'])->name('settings.taxes.delete');
    });

    Route::group(['prefix' => 'general_settings'], function () {

        Route::get('/', [GeneralSettingController::class, 'index'])->name('settings.general.index');
        Route::post('business/settings', [GeneralSettingController::class, 'businessSettings'])->name('settings.business.settings');
        Route::post('tax/settings', [GeneralSettingController::class, 'taxSettings'])->name('settings.tax.settings');
        Route::post('product/settings', [GeneralSettingController::class, 'productSettings'])->name('settings.product.settings');
        Route::post('contact/settings', [GeneralSettingController::class, 'contactSettings'])->name('settings.contact.settings');
        Route::post('sale/settings', [GeneralSettingController::class, 'saleSettings'])->name('settings.sale.settings');
        Route::post('pos/settings', [GeneralSettingController::class, 'posSettings'])->name('settings.pos.settings');
        Route::post('purchase/settings', [GeneralSettingController::class, 'purchaseSettings'])->name('settings.purchase.settings');
        Route::post('dashboard/settings', [GeneralSettingController::class, 'dashboardSettings'])->name('settings.dashboard.settings');
        Route::post('prefix/settings', [GeneralSettingController::class, 'prefixSettings'])->name('settings.prefix.settings');
        Route::post('system/settings', [GeneralSettingController::class, 'systemSettings'])->name('settings.system.settings');
        Route::post('module/settings', [GeneralSettingController::class, 'moduleSettings'])->name('settings.module.settings');
        Route::post('send/email/sms/settings', [GeneralSettingController::class, 'SendEmailSmsSettings'])->name('settings.send.email.sms.settings');
        Route::post('sms/settings', [GeneralSettingController::class, 'smsSettings'])->name('settings.sms.settings');
        Route::post('rp/settings', [GeneralSettingController::class, 'rewardPointSettings'])->name('settings.reward.point.settings');
    });

    Route::group(['prefix' => 'payment_methods'], function () {

        Route::get('/', [PaymentMethodController::class, 'index'])->name('settings.payment.method.index');
        Route::post('store', [PaymentMethodController::class, 'store'])->name('settings.payment.method.store');
        Route::get('edit/{id}', [PaymentMethodController::class, 'edit'])->name('settings.payment.method.edit');
        Route::post('update/{id}', [PaymentMethodController::class, 'update'])->name('settings.payment.method.update');
        Route::delete('delete/{id}', [PaymentMethodController::class, 'delete'])->name('settings.payment.method.delete');
    });

    Route::group(['prefix' => 'payment_method_settings'], function () {

        Route::get('/', [PaymentMethodSettingsController::class, 'index'])->name('settings.payment.method.settings.index');
        Route::post('update', [PaymentMethodSettingsController::class, 'update'])->name('settings.payment.method.settings.update');
    });

    Route::group(['prefix' => 'barcode_settings'], function () {

        Route::get('/', [BarcodeSettingController::class, 'index'])->name('settings.barcode.index');
        Route::get('create', [BarcodeSettingController::class, 'create'])->name('settings.barcode.create');
        Route::post('store', [BarcodeSettingController::class, 'store'])->name('settings.barcode.store');
        Route::get('edit/{id}', [BarcodeSettingController::class, 'edit'])->name('settings.barcode.edit');
        Route::post('update/{id}', [BarcodeSettingController::class, 'update'])->name('settings.barcode.update');
        Route::delete('delete/{id}', [BarcodeSettingController::class, 'delete'])->name('settings.barcode.delete');
        Route::get('set/default/{id}', [BarcodeSettingController::class, 'setDefault'])->name('settings.barcode.set.default');
    });

    Route::group(['prefix' => 'invoices'], function () {

        Route::group(['prefix' => 'schemas'], function () {

            Route::get('/', [InvoiceSchemaController::class, 'index'])->name('invoices.schemas.index');
            Route::post('store', [InvoiceSchemaController::class, 'store'])->name('invoices.schemas.store');
            Route::get('edit/{schemaId}', [InvoiceSchemaController::class, 'edit'])->name('invoices.schemas.edit');
            Route::post('update/{schemaId}', [InvoiceSchemaController::class, 'update'])->name('invoices.schemas.update');
            Route::delete('delete/{schemaId}', [InvoiceSchemaController::class, 'delete'])->name('invoices.schemas.delete');
            Route::get('set/default/{schemaId}', [InvoiceSchemaController::class, 'setDefault'])->name('invoices.schemas.set.default');
        });

        Route::group(['prefix' => 'layouts'], function () {

            Route::get('/', [InvoiceLayoutController::class, 'index'])->name('invoices.layouts.index');
            Route::get('create', [InvoiceLayoutController::class, 'create'])->name('invoices.layouts.create');
            Route::post('/', [InvoiceLayoutController::class, 'store'])->name('invoices.layouts.store');
            Route::get('edit/{layoutId}', [InvoiceLayoutController::class, 'edit'])->name('invoices.layouts.edit');
            Route::post('update/{layoutId}', [InvoiceLayoutController::class, 'update'])->name('invoices.layouts.update');
            Route::delete('delete/{layoutId}', [InvoiceLayoutController::class, 'delete'])->name('invoices.layouts.delete');
            Route::get('set/default/{schemaId}', [InvoiceLayoutController::class, 'setDefault'])->name('invoices.layouts.set.default');
        });
    });

    Route::group(['prefix' => 'cash_counter'], function () {

        Route::get('/', [CashCounterController::class, 'index'])->name('settings.cash.counter.index');
        Route::post('store', [CashCounterController::class, 'store'])->name('settings.payment.cash.counter.store');
        Route::get('edit/{id}', [CashCounterController::class, 'edit'])->name('settings.cash.counter.edit');
        Route::post('update/{id}', [CashCounterController::class, 'update'])->name('settings.cash.counter.update');
        Route::delete('delete/{id}', [CashCounterController::class, 'delete'])->name('settings.cash.counter.delete');
    });

    Route::group(['prefix' => 'release/note'], function () {

        Route::get('/', [ReleaseNoteController::class, 'index'])->name('settings.release.note.index');
    });
});

Route::group(['prefix' => 'users'], function () {

    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('all/users', [UserController::class, 'allUsers'])->name('users.all.Users');
    Route::get('create', [UserController::class, 'create'])->name('users.create');
    Route::get('all/roles', [UserController::class, 'allRoles'])->name('users.all.roles');
    Route::post('store', [UserController::class, 'store'])->name('users.store');
    Route::get('edit/{userId}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('update/{userId}', [UserController::class, 'update'])->name('users.update');
    Route::delete('delete/{userId}', [UserController::class, 'delete'])->name('users.delete');
    Route::get('show/{userId}', [UserController::class, 'show'])->name('users.show');

    Route::group(['prefix' => 'roles'], function () {

        Route::get('/', [RoleController::class, 'index'])->name('users.role.index');
        Route::get('all/roles', [RoleController::class, 'allRoles'])->name('users.role.all.roles');
        Route::get('create', [RoleController::class, 'create'])->name('users.role.create');
        Route::post('store', [RoleController::class, 'store'])->name('users.role.store');
        Route::get('edit/{roleId}', [RoleController::class, 'edit'])->name('users.role.edit');
        Route::post('update/{roleId}', [RoleController::class, 'update'])->name('users.role.update');
        Route::delete('delete/{roleId}', [RoleController::class, 'delete'])->name('users.role.delete');
    });

    Route::group(['prefix' => 'profile'], function () {

        Route::get('/', [UserProfileController::class, 'index'])->name('users.profile.index');
        Route::post('update', [UserProfileController::class, 'update'])->name('users.profile.update');
        Route::get('view/{id}', [UserProfileController::class, 'view'])->name('users.profile.view');
    });
});

Route::group(['prefix' => 'reports'], function () {

    Route::group(['prefix' => 'taxes'], function () {

        Route::get('/', [TaxReportController::class, 'index'])->name('reports.taxes.index');
        Route::get('get', [TaxReportController::class, 'getTaxReport'])->name('reports.taxes.get');
    });

    Route::group(['prefix' => 'user/activities/log'], function () {

        Route::get('/', [UserActivityLogReportController::class, 'index'])->name('reports.user.activities.log.index');
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

    Route::group(['prefix' => 'email',], function () {

        Route::get('settings', [EmailController::class, 'emailSettings'])->name('communication.email.settings');
        Route::post('settings/store', [EmailController::class, 'emailSettingsStore'])->name('communication.email.settings.store');
    });

    Route::group(['prefix' => 'sms',], function () {

        Route::get('settings', [SmsController::class, 'smsSettings'])->name('communication.sms.settings');
        Route::post('settings/store', [SmsController::class, 'smsSettingsStore'])->name('communication.sms.settings.store');
    });
});

Route::get('change/lang/{lang}', [DashboardController::class, 'changeLang'])->name('change.lang');

Route::get('maintenance/mode', function () {

    return view('maintenance/maintenance');
})->name('maintenance.mode');

Route::get('add-user', function () {

    $addAdmin = new User();
    $addAdmin->prefix = 'Mr.';
    $addAdmin->name = 'Super';
    $addAdmin->last_name = 'Admin';
    $addAdmin->email = 'superadmin@gmail.com';
    $addAdmin->username = 'superadmin';
    $addAdmin->password = Hash::make('12345');
    $addAdmin->role_type = 3;
    $addAdmin->role_permission_id = 1;
    $addAdmin->allow_login = 1;
    $addAdmin->save();
    //1=super_admin;2=admin;3=Other;

});

Route::get('/test', function () {

    //return str_pad(10, 10, "0", STR_PAD_LEFT);
    // $purchases = Purchase::all();
    // foreach ($purchases as $p) {
    //     $p->is_last_created = 0;
    //     $p->save();
    // }

    $customers = DB::table('customers')->get();

    foreach ($customers as $customer){

        $customerOpeningBalance = new CustomerOpeningBalance();
        $customerOpeningBalance->customer_id = $customer->id;
        $customerOpeningBalance->amount = $customer->opening_balance;
        $customerOpeningBalance->created_by_id = auth()->user()->id;
        $customerOpeningBalance->save();

        $customerCreditLimit = new CustomerCreditLimit();
        $customerCreditLimit->customer_id = $customer->id;
        $customerCreditLimit->credit_limit = $customer->credit_limit ? $customer->credit_limit : 0;
        $customerCreditLimit->created_by_id = auth()->user()->id;
        $customerCreditLimit->save();
    }

    $suppliers = DB::table('suppliers')->get();

    foreach ($suppliers as $supplier){

        $supplierOpeningBalance = new SupplierOpeningBalance();
        $supplierOpeningBalance->supplier_id = $supplier->id;
        $supplierOpeningBalance->amount = $supplier->opening_balance;
        $supplierOpeningBalance->created_by_id = auth()->user()->id;
        $supplierOpeningBalance->save();
    }
});

// Route::get('dbal', function() {
//     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
// });