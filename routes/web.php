<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Sale;
use Milon\Barcode\DNS1D;
use App\Models\AdminAndUser;
use App\Models\General_setting;
use Illuminate\Queue\Jobs\JobName;
use Illuminate\Support\Facades\DB;
use App\Models\ProductOpeningStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('change-current-password', [ResetPasswordController::class, 'resetCurrentPassword'])->name('password.updateCurrent');
//Product section route group
Route::group(['prefix' => 'product', 'namespace' => 'App\Http\Controllers'], function () {
    // Branch route group
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryControlle@index')->name('product.categories.index');
        Route::get('form/category', 'CategoryControlle@getAllFormCategory')->name('product.categories.all.category.form');
        Route::post('store', 'CategoryControlle@store')->name('product.categories.store');
        Route::get('edit/{categoryId}', 'CategoryControlle@edit')->name('product.categories.edit');
        Route::post('update', 'CategoryControlle@update')->name('product.categories.update');
        Route::delete('delete/{categoryId}', 'CategoryControlle@delete')->name('product.categories.delete');
    });

    Route::group(['prefix' => 'sub-categories'], function () {
        Route::get('/', 'SubCategoryController@index')->name('product.subcategories.index');
        Route::get('all', 'SubCategoryController@getAllSubCategory')->name('product.subcategories.all.subcategory');
        Route::get('form/category', 'SubCategoryController@getAllFormCategory')->name('product.subcategories.all.category.form');
        Route::post('store', 'SubCategoryController@store')->name('product.subcategories.store');
        Route::post('update', 'SubCategoryController@update')->name('product.subcategories.update');
        Route::delete('delete/{categoryId}', 'SubCategoryController@delete')->name('product.subcategories.delete');
        Route::get('edit/{id}', 'SubCategoryController@edit');
    });

    // Branch route group
    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', 'BrandController@index')->name('product.brands.index');
        Route::get('all', 'BrandController@getAllBrand')->name('product.brands.all.brand');
        Route::post('store', 'BrandController@store')->name('product.brands.store');
        Route::post('update', 'BrandController@update')->name('product.brands.update');
        Route::delete('delete/{brandId}', 'BrandController@delete')->name('product.brands.delete');
        Route::get('edit/{id}', 'BrandController@edit');
    });

    // products route group
    Route::group(['prefix' => '/'], function () {
        Route::get('all', 'ProductController@allProduct')->name('products.all.product');
        Route::get('view/{productId}', 'ProductController@view')->name('products.view');
        Route::get('get/all/product', 'ProductController@getAllProduct')->name('products.get.all.product');
        Route::get('add', 'ProductController@create')->name('products.add.view');
        Route::get('get/form/part/{type}', 'ProductController@getFormPart');
        Route::post('store', 'ProductController@store')->name('products.add.store');
        Route::get('edit/{productId}', 'ProductController@edit')->name('products.edit');
        Route::get('product/variants/{productId}', 'ProductController@getProductVariants')->name('products.get.product.variants');
        Route::get('combo/product/{productId}', 'ProductController@getComboProducts')->name('products.get.combo.products');
        Route::post('update/{productId}', 'ProductController@update')->name('products.update');
        Route::get('default/profit', 'ProductController@defaultProfit')->name('products.add.get.default.profit');

        Route::delete('delete/{productId}', 'ProductController@delete')->name('products.delete');
        Route::delete('multiple/delete', 'ProductController@multipleDelete')->name('products.multiple.delete');
        Route::get('all/form/brand', 'ProductController@allFromBrand')->name('products.add.get.all.form.brand');
        Route::get('all/form/category', 'ProductController@getAllFormCategories')->name('products.add.get.all.form.categories');
        Route::get('all/form/vat', 'ProductController@getAllFormTaxes')->name('products.add.get.all.form.taxes');
        Route::get('all/form/units', 'ProductController@getAllFormUnits')->name('products.add.get.all.form.units');
        Route::get('all/form/warranties', 'ProductController@getAllFormWarrties')->name('products.add.get.all.form.warranties');
        Route::get('all/form/variant', 'ProductController@getAllFormVariants')->name('products.add.get.all.from.variant');
        Route::get('search/product/{productCode}', 'ProductController@searchProduct');
        Route::get('get/product/stock/{productId}', 'ProductController@getProductStock');
        Route::get('change/status/{productId}', 'ProductController@changeStatus')->name('products.change.status');
        Route::get('check/purchase/generate/barcode/{productId}', 'ProductController@chackPurchaseAndGenerateBarcode')->name('products.check.purchase.and.generate.barcode');

        Route::get('get/opening/stock/{productId}', 'ProductController@openingStock')->name('products.opening.stock');
        Route::post('opening/stock/update/{productId}', 'ProductController@openingStockUpdate')->name('products.opening.stock.update');
        Route::post('add/category', 'ProductController@addCategory')->name('products.add.category');
        Route::post('add/brand', 'ProductController@addBrand')->name('products.add.brand');
        Route::post('add/unit', 'ProductController@addUnit')->name('products.add.unit');
        Route::post('add/warranty', 'ProductController@addWarranty')->name('products.add.warranty');
    });

    // Variants route group
    Route::group(['prefix' => 'variants'], function () {
        Route::get('/', 'BulkVariantController@index')->name('product.variants.index');
        Route::get('all', 'BulkVariantController@getAllVariant')->name('product.variants.all.variant');
        Route::post('store', 'BulkVariantController@store')->name('product.variants.store');
        Route::post('update', 'BulkVariantController@update')->name('product.variants.update');
        Route::delete('delete/{brandId}', 'BulkVariantController@delete')->name('product.variants.delete');
    });

    // Barcode route group
    Route::group(['prefix' => 'barcode'], function () {
        Route::get('/', 'BarcodeController@index')->name('barcode.index');
        Route::get('supplier/products', 'BarcodeController@supplierProduct')->name('barcode.supplier.get.products');
        Route::get('genereate/completed', 'BarcodeController@genereateCompleted')->name('barcode.genereate.completed');
        Route::post('multiple/genereate/completed', 'BarcodeController@multipleGenereateCompleted')->name('barcode.multiple.genereate.completed');
        Route::get('search/product/{searchKeyword}', 'BarcodeController@searchProduct');
        Route::get('get/selected/product/{productId}', 'BarcodeController@getSelectedProduct');
        Route::get('get/selected/product/variant/{productId}/{variantId}', 'BarcodeController@getSelectedProductVariant');
        Route::get('generate/product/barcode/{productId}', 'BarcodeController@genrateProductBarcode')->name('products.generate.product.barcode');
        Route::get('get/spacific/supplier/product/{productId}', 'BarcodeController@getSpacificSupplierProduct')->name('barcode.get.spacific.supplier.product');

        // Genereate barcodes on purchase.
        Route::get('purchase/products/{purchaseId}', 'BarcodeController@onPurchaseBarcode')->name('barcode.on.purchase.barcode');
        Route::get('get/purchase/products/{purchaseId}', 'BarcodeController@getPurchaseProduct')->name('barcode.get.purchase.products');
    });

    // Import product route group
    // Barcode route group
    Route::group(['prefix' => 'imports'], function () {
        Route::get('create', 'ProductImportController@create')->name('product.import.create');
        Route::post('store', 'ProductImportController@store')->name('product.import.store');
    });

    // Warranty route group
    Route::group(['prefix' => 'warranties'], function () {
        Route::get('/', 'WarrantyController@index')->name('product.warranties.index');
        Route::get('all', 'WarrantyController@allWarranty')->name('product.warranties.all.warranty');
        Route::post('store', 'WarrantyController@store')->name('product.warranties.store');
        Route::post('update', 'WarrantyController@update')->name('product.warranties.update');
        Route::delete('delete/{warrantyId}', 'WarrantyController@delete')->name('product.warranties.delete');
    });
});

// Contact route group
Route::group(['prefix' => 'contacts', 'namespace' => 'App\Http\Controllers'], function () {
    // Supplier route group
    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('/', 'SupplierController@index')->name('contacts.supplier.index');
        Route::get('get/all/suppliers', 'SupplierController@getAllSupplier')->name('contacts.supplier.get.all.supplier');
        Route::get('add', 'SupplierController@create')->name('contacts.supplier.create');
        Route::post('store', 'SupplierController@store')->name('contacts.supplier.store');
        Route::post('update', 'SupplierController@update')->name('contacts.supplier.update');
        Route::get('get/supplier/{supplierId}', 'SupplierController@getSupplier')->name('contacts.supplier.get.supplier');
        Route::delete('delete/{supplierId}', 'SupplierController@delete')->name('contacts.supplier.delete');
        Route::get('change/status/{supplierId}', 'SupplierController@changeStatus')->name('contacts.supplier.change.status');
        Route::get('view/{supplierId}', 'SupplierController@view');
        Route::get('all/info/{supplierId}', 'SupplierController@SupplierAllInfo')->name('contacts.supplier.all.info');
        Route::get('payment/list/{supplierId}', 'SupplierController@paymentList')->name('contacts.supplier.payment.list');
        Route::get('purchase/list/{supplierId}', 'SupplierController@purchaseList')->name('contacts.supplier.purchase.list');
        Route::get('ledger/{supplierId}', 'SupplierController@ledger');
        Route::get('contact/info/{supplierId}', 'SupplierController@contactInfo');
        Route::get('purchases/{supplierId}', 'SupplierController@purchases');
        Route::get('payment/{supplierId}', 'SupplierController@payment')->name('suppliers.payment');
        Route::post('payment/{supplierId}', 'SupplierController@paymentAdd')->name('suppliers.payment.add');

        Route::get('return/payment/{supplierId}', 'SupplierController@returnPayment')->name('suppliers.return.payment');
        Route::post('return/payment/{supplierId}', 'SupplierController@returnPaymentAdd')->name('suppliers.return.payment.add');
    });

    // Customers route group
    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', 'CustomerController@index')->name('contacts.customer.index');
        Route::get('get/all/customer', 'CustomerController@getAllCustomer')->name('contacts.customer.get.all.customer');
        Route::get('get/all/group', 'CustomerController@getAllGroup')->name('contacts.customer.get.all.group');
        Route::get('add', 'CustomerController@create')->name('contacts.customer.create');
        Route::post('store', 'CustomerController@store')->name('contacts.customer.store');
        Route::get('edit/{customerId}', 'CustomerController@edit')->name('contacts.customer.edit');
        Route::post('update', 'CustomerController@update')->name('contacts.customer.update');
        Route::get('get/customer/{customerId}', 'CustomerController@getCustomer')->name('contacts.customer.get.customer');
        Route::delete('delete/{customerId}', 'CustomerController@delete')->name('contacts.customer.delete');
        Route::get('change/status/{customerId}', 'CustomerController@changeStatus')->name('contacts.customer.change.status');
        Route::get('view/{customerId}', 'CustomerController@view');
        Route::get('all/info/{customerId}', 'CustomerController@cutomerAllInfo')->name('contacts.customer.all.info');
        Route::get('ledger/list/{customerId}', 'CustomerController@ledgerList')->name('contacts.customer.ledger.list');
        Route::get('ledger/{customerId}', 'CustomerController@ledger');
        Route::get('contact/info/{customerId}', 'CustomerController@contactInfo');
        Route::get('payment/{customerId}', 'CustomerController@payment')->name('customers.payment');
        Route::post('payment/{customerId}', 'CustomerController@paymentAdd')->name('customers.payment.add');

        Route::get('return/payment/{customerId}', 'CustomerController@returnPayment')->name('customers.return.payment');
        Route::post('return/payment/{customerId}', 'CustomerController@returnPaymentAdd')->name('customers.return.payment.add');

        Route::group(['prefix' => 'money/receipt'], function () {
            Route::get('/voucher/list/{customerId}', 'MoneyReceiptController@moneyReceiptList')->name('money.receipt.voucher.list');

            Route::get('create/{customerId}', 'MoneyReceiptController@moneyReceiptCreate')->name('money.receipt.voucher.create');

            Route::post('store/{customerId}', 'MoneyReceiptController@store')->name('money.receipt.voucher.store');

            Route::get('voucher/print/{receiptId}', 'MoneyReceiptController@moneyReceiptPrint')->name('money.receipt.voucher.print');

            Route::get('voucher/print/{receiptId}', 'MoneyReceiptController@moneyReceiptPrint')->name('money.receipt.voucher.print');


            Route::get('voucher/status/change/modal/{receiptId}', 'MoneyReceiptController@changeStatusModal')->name('money.receipt.voucher.status.change.modal');

            Route::post('voucher/status/change/{receiptId}', 'MoneyReceiptController@changeStatus')->name('money.receipt.voucher.status.change');

            Route::delete('voucher/delete/{receiptId}', 'MoneyReceiptController@delete')->name('money.receipt.voucher.delete');
        });

        Route::group(['prefix' => 'groups'], function () {
            Route::get('/', 'CustomerGroupController@index')->name('contacts.customers.groups.index');
            Route::get('all/groups', 'CustomerGroupController@allBanks')->name('contacts.customers.groups.all.group');
            Route::post('store', 'CustomerGroupController@store')->name('contacts.customers.groups.store');
            Route::post('update', 'CustomerGroupController@update')->name('contacts.customers.groups.update');
            Route::delete('delete/{groupId}', 'CustomerGroupController@delete')->name('customers.groups.delete');
        });
    });
});

// Purchase route group
Route::group(['prefix' => 'purchases', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('v2', 'PurchaseController@index_v2')->name('purchases.index_v2');
    Route::get('show/{purchaseId}', 'PurchaseController@show')->name('purchases.show');
    Route::get('create', 'PurchaseController@create')->name('purchases.create');
    Route::post('store', 'PurchaseController@store')->name('purchases.store');
    Route::get('edit/{purchaseId}', 'PurchaseController@edit')->name('purchases.edit');
    Route::get('editable/purchase/{purchaseId}', 'PurchaseController@editablePurchase')->name('purchases.get.editable.purchase');
    Route::post('update', 'PurchaseController@update')->name('purchases.update');
    Route::get('get/all/supplier', 'PurchaseController@getAllSupplier')->name('purchases.get.all.supplier');
    Route::get('get/all/branch', 'PurchaseController@getAllBranch')->name('purchases.get.all.branch');
    Route::get('get/all/warehouse', 'PurchaseController@getAllWarehouse')->name('purchases.get.all.warehouse');
    Route::get('get/all/unit', 'PurchaseController@getAllUnit')->name('purchases.get.all.unites');
    Route::get('get/all/tax', 'PurchaseController@getAllTax')->name('purchases.get.all.taxes');
    Route::get('search/product/{product_code}', 'PurchaseController@searchProduct');
    Route::delete('delete/{purchaseId}', 'PurchaseController@delete')->name('purchase.delete');
    Route::get('change/status/modal/{purchaseId}', 'PurchaseController@changeStatusModal')->name('purchases.change.status.modal');
    Route::post('change/status/{purchaseId}', 'PurchaseController@changeStatus')->name('purchases.change.status');
    Route::post('add/supplier', 'PurchaseController@addSupplier')->name('purchases.add.supplier');
    Route::get('add/product/modal/view', 'PurchaseController@addProductModalVeiw')->name('purchases.add.product.modal.view');
    Route::post('add/product', 'PurchaseController@addProduct')->name('purchases.add.product');
    Route::get('recent/product/{productId}', 'PurchaseController@getRecentProduct');
    Route::get('add/quick/supplier/modal', 'PurchaseController@addQuickSupplierModal')->name('purchases.add.quick.supplier.modal');

    Route::get('payment/modal/{purchaseId}', 'PurchaseController@paymentModal')->name('purchases.payment.modal');
    Route::post('payment/store/{purchaseId}', 'PurchaseController@paymentStore')->name('purchases.payment.store');
    Route::get('payment/edit/{paymentId}', 'PurchaseController@paymentEdit')->name('purchases.payment.edit');
    Route::post('payment/update/{paymentId}', 'PurchaseController@paymentUpdate')->name('purchases.payment.update');
    Route::get('return/payment/modal/{purchaseId}', 'PurchaseController@returnPaymentModal')->name('purchases.return.payment.modal');
    Route::post('return/payment/store/{purchaseId}', 'PurchaseController@returnPaymentStore')->name('purchases.return.payment.store');
    Route::get('return/payment/edit/{paymentId}', 'PurchaseController@returnPaymentEdit')->name('purchases.return.payment.edit');
    Route::post('return/payment/update/{paymentId}', 'PurchaseController@returnPaymentUpdate')->name('purchases.return.payment.update');

    Route::get('payment/details/{paymentId}', 'PurchaseController@paymentDetails')->name('purchases.payment.details');
    Route::delete('payment/delete/{paymentId}', 'PurchaseController@paymentDelete')->name('purchases.payment.delete');
    Route::get('payment/list/{purchaseId}', 'PurchaseController@paymentList')->name('purchase.payment.list');

    // Purchase Return route
    Route::group(['prefix' => 'returns'], function () {
        Route::get('/', 'PurchaseReturnController@index')->name('purchases.returns.index');
        Route::get('show/{returnId}', 'PurchaseReturnController@show')->name('purchases.returns.show');
        Route::get('add/{purchaseId}', 'PurchaseReturnController@create')->name('purchases.returns.create');
        Route::get('get/purchase/{purchaseId}', 'PurchaseReturnController@getPurchase')->name('purchases.returns.get.purchase');
        Route::post('store/{purchaseId}', 'PurchaseReturnController@store')->name('purchases.returns.store');
        Route::delete('delete/{purchaseReturnId}', 'PurchaseReturnController@delete')->name('purchases.returns.delete');

        Route::get('create', 'PurchaseReturnController@supplierReturn')->name('purchases.returns.supplier.return');
        Route::get('search/product/{productCode}/{warehouseId}', 'PurchaseReturnController@searchProduct');
        Route::get('search/product/in/branch/{productCode}/{branchId}', 'PurchaseReturnController@searchProductInBranch');
        Route::get('check/warehouse/variant/qty/{productId}/{variantId}/{warehouseId}', 'PurchaseReturnController@checkWarehouseProductVariant');
        Route::get('check/branch/variant/qty/{productId}/{variantId}/{branchId}', 'PurchaseReturnController@checkBranchProductVariant');
        Route::post('supplier/return/store', 'PurchaseReturnController@supplierReturnStore')->name('purchases.returns.supplier.return.store');
        Route::get('supplier/return/edit/{purchaseReturnId}', 'PurchaseReturnController@supplierReturnEdit')->name('purchases.returns.supplier.return.edit');
        Route::get('get/editable/supplierReturn/{purchaseReturnId}', 'PurchaseReturnController@getEditableSupplierReturn')->name('purchases.return.get.editable.supplier.return');
        Route::post('supplier/return/update/{purchaseReturnId}', 'PurchaseReturnController@supplierReturnUpdate')->name('purchases.returns.supplier.return.update');
    });
});

// Sale route group
Route::group(['prefix' => 'sales', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('v2', 'SaleController@index2')->name('sales.index2');
    Route::get('show/{saleId}', 'SaleController@show')->name('sales.show');
    Route::get('print/{saleId}', 'SaleController@print')->name('sales.print');
    Route::get('packing/Slip/{saleId}', 'SaleController@packingSlip')->name('sales.packing.slip');
    Route::get('drafts', 'SaleController@drafts')->name('sales.drafts');
    Route::get('draft/details/{draftId}', 'SaleController@draftDetails')->name('sales.drafts.details');
    Route::get('quotations', 'SaleController@quotations')->name('sales.quotations');
    Route::get('quotation/details/{quotationId}', 'SaleController@quotationDetails')->name('sales.quotations.details');
    Route::get('create', 'SaleController@create')->name('sales.create');
    Route::post('store', 'SaleController@store')->name('sales.store');
    Route::get('edit/{saleId}', 'SaleController@edit')->name('sales.edit');
    Route::get('editable/sale/{saleId}', 'SaleController@editableSale')->name('sales.get.editable.sale');
    Route::post('update/{saleId}', 'SaleController@update')->name('sales.update');
    Route::get('get/all/customer', 'SaleController@getAllCustomer')->name('sales.get.all.customer');
    Route::get('customer_info/{customerId}', 'SaleController@customerInfo');
    Route::get('get/all/users', 'SaleController@getAllUser')->name('sales.get.all.users');
    Route::get('get/all/branches', 'SaleController@getAllBranches')->name('sales.get.all.branches');
    Route::get('get/all/warehouse', 'SaleController@getAllWarehosue')->name('sales.get.all.warehouses');
    Route::get('get/all/unit', 'SaleController@getAllUnit')->name('sales.get.all.unites');
    Route::get('get/all/tax', 'SaleController@getAllTax')->name('sales.get.all.taxes');
    Route::get('search/product/{product_code}/{branch_id}', 'SaleController@searchProduct');
    Route::get('search/product/in/warehouse/{product_code}/{warehouse_id}', 'SaleController@searchProductInWarehouse');
    Route::delete('delete/{saleId}', 'SaleController@delete')->name('sales.delete');
    Route::get('edit/shipment/{saleId}', 'SaleController@editShipment')->name('sales.shipment.edit');
    Route::post('update/shipment/{saleId}', 'SaleController@updateShipment')->name('sales.shipment.update');
    Route::post('change/status/{saleId}', 'SaleController@changeStatus')->name('sales.change.status');
    Route::get('filter/draft', 'SaleController@filterDraft')->name('sales.filter.draft');
    Route::get('check/branch/variant/qty/{product_id}/{variant_id}/{branch_id}', 'SaleController@checkBranchProductVariant');
    Route::get('check/single/product/stock/{product_id}/{branch_id}', 'SaleController@checkBranchSingleProductStock');

    Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{branch_id}', 'SaleController@checkBranchProductVariantInWarehouse');
    Route::get('check/single/product/stock/in/warehouse/{product_id}/{branch_id}', 'SaleController@checkBranchSingleProductStockInWarehouse');
    Route::get('shipments', 'SaleController@shipments')->name('sales.shipments');

    // Sale payment route
    Route::get('payment/{saleId}', 'SaleController@paymentModal')->name('sales.payment.modal');
    Route::post('payment/add/{saleId}', 'SaleController@paymentAdd')->name('sales.payment.add');

    Route::get('payment/view/{saleId}', 'SaleController@viewPayment')->name('sales.payment.view');
    Route::get('payment/edit/{paymentId}', 'SaleController@paymentEdit')->name('sales.payment.edit');
    Route::post('payment/update/{paymentId}', 'SaleController@paymentUpdate')->name('sales.payment.update');
    Route::get('payment/details/{paymentId}', 'SaleController@paymentDetails')->name('sales.payment.details');

    Route::delete('payment/delete/{paymentId}', 'SaleController@paymentDelete')->name('sales.payment.delete');

    Route::get('return/payment/{saleId}', 'SaleController@returnPaymentModal')->name('sales.return.payment.modal');
    Route::post('return/payment/add/{saleId}', 'SaleController@returnPaymentAdd')->name('sales.return.payment.add');
    Route::get('return/payment/edit/{paymentId}', 'SaleController@returnPaymentEdit')->name('sales.return.payment.edit');
    Route::post('return/payment/update/{paymentId}', 'SaleController@returnPaymentUpdate')->name('sales.return.payment.update');

    Route::get('add/product/modal/view', 'SaleController@addProductModalVeiw')->name('sales.add.product.modal.view');
    Route::get('get/all/sub/category/{categoryId}','SaleController@getAllSubCategory');
    Route::post('add/product', 'SaleController@addProduct')->name('sales.add.product');
    Route::get('get/recent/product/{branch_id}/{warehouse_id}/{product_id}', 'SaleController@getRecentProduct');

    // Sale return route
    Route::group(['prefix' => 'returns'], function () {
        Route::get('/', 'SaleReturnController@index')->name('sales.returns.index');
        Route::get('show/{returnId}', 'SaleReturnController@show')->name('sales.returns.show');
        Route::get('add/{saleId}', 'SaleReturnController@create')->name('sales.returns.create');
        Route::get('get/sale/{saleId}', 'SaleReturnController@getSale')->name('sales.returns.get.sale');
        Route::post('store/{saleId}', 'SaleReturnController@store')->name('sales.returns.store');
        Route::delete('delete/{saleReturnId}', 'SaleReturnController@delete')->name('sales.returns.delete');
    });

    //Pos cash register routes
    Route::group(['prefix' => 'cash/register'], function () {
        Route::get('/', 'CashRegisterController@create')->name('sales.cash.register.create');
        Route::post('store', 'CashRegisterController@store')->name('sales.cash.register.store');
        Route::get('close/cash/registser/modal/view', 'CashRegisterController@closeCashRegisterModalView')->name('sales.cash.register.close.modal.view');
        Route::get('close/cash/registser/details', 'CashRegisterController@cashRegisterDetails')->name('sales.cash.register.details');
        Route::post('close', 'CashRegisterController@close')->name('sales.cash.register.close');
    });

    // Pos routes
    Route::group(['prefix' => 'pos'], function () {
        Route::get('create', 'POSController@create')->name('sales.pos.create');
        Route::get('product/list', 'POSController@posProductList')->name('sales.pos.product.list');
        Route::post('store', 'POSController@store')->name('sales.pos.store');
        Route::get('pick/hold/invoice', 'POSController@pickHoldInvoice');
        Route::get('edit/{saleId}', 'POSController@edit')->name('sales.pos.edit');
        Route::get('invoice/products/{saleId}', 'POSController@invoiceProducts')->name('sales.pos.invoice.products');
        Route::post('update', 'POSController@update')->name('sales.pos.update');
        Route::get('recent/sales', 'POSController@recentSales');
        Route::get('recent/quotations', 'POSController@recentQuotations');
        Route::get('recent/drafts', 'POSController@recentDrafts');
        Route::get('suspended/sale/list', 'POSController@suspendedList')->name('sales.pos.suspended.list');
        Route::get('branch/stock', 'POSController@branchStock')->name('sales.pos.branch.stock');
        Route::get('add/customer/modal', 'POSController@addQuickCustomerModal')->name('sales.pos.add.quick.customer.modal');
        Route::post('add/customer', 'POSController@addCustomer')->name('sales.pos.add.customer');
        Route::get('get/recent/product/{branch_id}/{warehouse_id}/{product_id}', 'POSController@getRecentProduct');
        Route::get('close/cash/registser/modal/view', 'POSController@close');
    });
});

//Transfer stock to branch all route
Route::group(['prefix' => 'transfer/stocks', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'TransferToBranchController@index')->name('transfer.stock.to.branch.index');
    Route::get('show/{transferId}', 'TransferToBranchController@show')->name('transfer.stock.to.branch.show');
    Route::get('transfer/products/{transferId}', 'TransferToBranchController@transferProduct');
    Route::get('all/transfer/', 'TransferToBranchController@allTransfer')->name('transfer.stock.to.branch.all.transfer');
    Route::get('create', 'TransferToBranchController@create')->name('transfer.stock.to.branch.create');
    Route::post('store', 'TransferToBranchController@store')->name('transfer.stock.to.branch.store');
    Route::get('get/all/warehouse', 'TransferToBranchController@getAllWarehouse')->name('transfer.stock.to.branch.all.warehouse');
    Route::get('edit/{transferId}', 'TransferToBranchController@edit')->name('transfer.stock.to.branch.edit');
    Route::get('get/editable/transfer/{transferId}', 'TransferToBranchController@editableTransfer')->name('transfer.stock.to.branch.editable.transfer');
    Route::post('update/{transferId}', 'TransferToBranchController@update')->name('transfer.stock.to.branch.update');
    Route::delete('delete/{transferId}', 'TransferToBranchController@delete')->name('transfer.stock.to.branch.delete');
    Route::get('sarach/product/{product_code}/{warehouse_id}', 'TransferToBranchController@productSearch');
    Route::get('check/warehouse/variant/qty/{product_id}/{variant_id}/{warehouse_id}', 'TransferToBranchController@checkWarehouseProductVariant');
    Route::get('check/warehouse/qty/{product_id}/{warehouse_id}', 'TransferToBranchController@checkWarehouseSingleProduct');
    // Receive stock from warehouse **route group**
    Route::group(['prefix' => 'receive'], function () {
        Route::get('/', 'WarehouseReceiveStockController@index')->name('transfer.stocks.to.branch.receive.stock.index');
        Route::get('show/{sendStockId}', 'WarehouseReceiveStockController@show')->name('transfer.stocks.to.branch.receive.stock.show');
        Route::get('process/{sendStockId}', 'WarehouseReceiveStockController@receiveProducessView')->name('transfer.stocks.to.branch.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', 'WarehouseReceiveStockController@receivableStock')->name('transfer.stocks.to.branch.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', 'WarehouseReceiveStockController@receiveProcessSave')->name('transfer.stocks.to.branch.receive.stock.process.save');
    });
});

//Stock adjustment to branch all route
Route::group(['prefix' => 'stock/adjustments', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'StockAdjustmentController@index')->name('stock.adjustments.index');
    Route::get('show/{adjustmentId}', 'StockAdjustmentController@show')->name('stock.adjustments.show');
    Route::get('create', 'StockAdjustmentController@create')->name('stock.adjustments.create');
    Route::post('store', 'StockAdjustmentController@store')->name('stock.adjustments.store');
    Route::get('search/product/in/warehouse/{keyword}/{warehouse_id}', 'StockAdjustmentController@searchProductInWarehouse');
    Route::get('search/product/{keyword}/{branch_id}', 'StockAdjustmentController@searchProduct');

    Route::get('check/single/product/stock/{product_id}/{branch_id}', 'StockAdjustmentController@checkSingleProductStock');
    Route::get('check/single/product/stock/in/warehouse/{product_id}/{warehouse_id}', 'StockAdjustmentController@checkSingleProductStockInWarehouse');

    Route::get('check/variant/product/stock/{product_id}/{variant_id}/{branch_id}', 'StockAdjustmentController@checkVariantProductStock');
    Route::get('check/variant/product/stock/in/warehouse/{product_id}/{variant_id}/{warehouse_id}', 'StockAdjustmentController@checkVariantProductStockInWarehouse');
    Route::delete('delete/{adjustmentId}', 'StockAdjustmentController@delete')->name('stock.adjustments.delete');
});

//Transfer stok to warehouse all route
Route::group(['prefix' => 'transfer/stocks/to/warehouse', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'TransferToWarehouseController@index')->name('transfer.stock.to.warehouse.index');
    Route::get('transfer/products/{transferId}', 'TransferToWarehouseController@transferProduct');
    Route::get('all/transfer/', 'TransferToWarehouseController@allTransfer')->name('transfer.stock.to.warehouse.all.transfer');
    Route::get('create', 'TransferToWarehouseController@create')->name('transfer.stock.to.warehouse.create');
    Route::post('store', 'TransferToWarehouseController@store')->name('transfer.stock.to.warehouse.store');
    Route::get('get/all/warehouse', 'TransferToWarehouseController@getAllWarehouse')->name('transfer.stock.to.warehouse.all.warehouse');
    Route::get('edit/{transferId}', 'TransferToWarehouseController@edit')->name('transfer.stock.to.warehouse.edit');
    Route::get('get/editable/transfer/{transferId}', 'TransferToWarehouseController@editableTransfer')->name('transfer.stock.to.warehouse.editable.transfer');
    Route::post('update/{transferId}', 'TransferToWarehouseController@update')->name('transfer.stock.to.warehouse.update');
    Route::delete('delete/{transferId}', 'TransferToWarehouseController@delete')->name('transfer.stock.to.warehouse.delete');
    Route::get('sarach/product/{product_code}/{branch_id}', 'TransferToWarehouseController@productSearch');
    Route::get('check/branch/variant/qty/{product_id}/{variant_id}/{branch_id}', 'TransferToWarehouseController@checkBranchProductVariant');

    // Receive stock from branch **route group**
    Route::group(['prefix' => 'receive'], function () {
        Route::get('/', 'BranchReceiveStockController@index')->name('transfer.stocks.to.warehouse.receive.stock.index');
        Route::get('show/{sendStockId}', 'BranchReceiveStockController@show')->name('transfer.stocks.to.warehouse.receive.stock.show');
        Route::get('all/send/stocks', 'BranchReceiveStockController@allSendStock')->name('transfer.stocks.to.warehouse.receive.stock.all.send.stocks');
        Route::get('process/{sendStockId}', 'BranchReceiveStockController@receiveProducessView')->name('transfer.stocks.to.warehouse.receive.stock.process.view');
        Route::get('receivable/stock/{sendStockId}', 'BranchReceiveStockController@receivableStock')->name('transfer.stocks.to.warehouse.receive.stock.get.receivable.stock');
        Route::post('process/save/{sendStockId}', 'BranchReceiveStockController@receiveProcessSave')->name('transfer.stocks.to.warehouse.receive.stock.process.save');
    });
});

// Expense route group
Route::group(['prefix' => 'expanses', 'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'ExpanseController@index')->name('expanses.index');
    Route::get('all/expanse', 'ExpanseController@allExpanse')->name('expanses.all.expanse');
    Route::get('filter', 'ExpanseController@filter')->name('expanses.filter');
    Route::get('create', 'ExpanseController@create')->name('expanses.create');
    Route::post('store', 'ExpanseController@store')->name('expanses.store');
    Route::get('edit/{expanseId}', 'ExpanseController@edit')->name('expanses.edit');
    Route::post('update/{expenseId}', 'ExpanseController@update')->name('expanses.update');
    Route::delete('delete/{expanseId}', 'ExpanseController@delete')->name('expanses.delete');
    Route::get('all/categories', 'ExpanseController@allCategories')->name('expanses.all.categories');
    Route::get('all/admins', 'ExpanseController@allAdmins')->name('expanses.all.admins');
    Route::get('payment/modal/{expenseId}', 'ExpanseController@paymentModal')->name('expanses.payment.modal');
    Route::post('payment/{expenseId}', 'ExpanseController@payment')->name('expanses.payment');
    Route::get('payment/view/{expenseId}', 'ExpanseController@paymentView')->name('expanses.payment.view');
    Route::get('payment/details/{paymentId}', 'ExpanseController@paymentDetails')->name('expanses.payment.details');
    Route::get('payment/edit/{paymentId}', 'ExpanseController@paymentEdit')->name('expanses.payment.edit');
    Route::post('payment/update/{paymentId}', 'ExpanseController@paymentUpdate')->name('expanses.payment.update');
    Route::delete('payment/delete/{paymentId}', 'ExpanseController@paymentDelete')->name('expanses.payment.delete');

    // Expanse category route group
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'ExpanseCategoryController@index')->name('expanses.categories.index');
        Route::get('all/categories', 'ExpanseCategoryController@allCategory')->name('expanses.categories.all.category');
        Route::post('store', 'ExpanseCategoryController@store')->name('expanses.categories.store');
        Route::post('update', 'ExpanseCategoryController@update')->name('expanses.categories.update');
        Route::delete('delete/{categoryId}', 'ExpanseCategoryController@delete')->name('expanses.categories.delete');
    });
});

Route::group(['prefix' => 'accounting', 'namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'banks'], function () {
        Route::get('/', 'BankController@index')->name('accounting.banks.index');
        Route::get('all/banks', 'BankController@allBanks')->name('accounting.banks.all.bank');
        Route::post('store', 'BankController@store')->name('accounting.banks.store');
        Route::post('update', 'BankController@update')->name('accounting.banks.update');
        Route::delete('delete/{bankId}', 'BankController@delete')->name('accounting.banks.delete');
    });

    Route::group(['prefix' => 'types'], function () {
        Route::get('/', 'AccountTypeController@index')->name('accounting.types.index');
        Route::get('all/types', 'AccountTypeController@allTypes')->name('accounting.types.all.type');
        Route::post('store', 'AccountTypeController@store')->name('accounting.types.store');
        Route::post('update', 'AccountTypeController@update')->name('accounting.types.update');
        Route::delete('delete/{typeId}', 'AccountTypeController@delete')->name('accounting.types.delete');
        Route::get('change/status/{typeId}', 'AccountTypeController@changeStatus')->name('accounting.types.change.status');
    });

    Route::group(['prefix' => 'accounts'], function () {
        Route::get('/', 'AccountController@index')->name('accounting.accounts.index');
        Route::get('all/account', 'AccountController@allAccounts')->name('accounting.accounts.all.account');
        Route::get('account/book/{accountId}', 'AccountController@accountBook')->name('accounting.accounts.book');
        Route::post('store', 'AccountController@store')->name('accounting.accounts.store');
        Route::post('update', 'AccountController@update')->name('accounting.accounts.update');
        Route::delete('delete/{accountId}', 'AccountController@delete')->name('accounting.accounts.delete');
        Route::get('change/status/{accountId}', 'AccountController@changeStatus')->name('accounting.accounts.change.status');
        Route::get('all/banks', 'AccountController@allBanks')->name('accounting.accounts.all.banks');
        Route::get('all/account/types', 'AccountController@allAccountTypes')->name('accounting.accounts.all.account.types');
        Route::get('all/form/account', 'AccountController@allFromAccount')->name('accounting.accounts.all.form.account');
        Route::get('filter/account', 'AccountController@filterAccount')->name('accounting.accounts.filter');
        Route::post('fund/transfer', 'AccountController@fundTransfer')->name('accounting.accounts.fund.transfer');

        Route::post('deposit', 'AccountController@deposit')->name('accounting.accounts.fund.deposit');

        Route::get('account/cash/flows/{accountId}', 'AccountController@accountCashflows')->name('accounting.accounts.account.cash.flows');
        Route::get('account/cash/flow/filter/{accountId}', 'AccountController@accountCashflowFilter')->name('accounting.accounts.account.cash.flow.filter');
        Route::delete('delete/cash/flow/{cashFlowId}', 'AccountController@deleteCashFlow')->name('accounting.accounts.account.delete.cash.flow');
    });

    Route::group(['prefix' => '/'], function () {
        Route::get('balance/sheet', 'AccountingRelatedSectionController@balanceSheet')->name('accounting.balance.sheet');
        Route::get('balance/sheet/amounts', 'AccountingRelatedSectionController@balanceSheetAmounts')->name('accounting.balance.sheet.amounts');
        Route::get('trial/balance', 'AccountingRelatedSectionController@trialBalance')->name('accounting.trial.balance');
        Route::get('trial/balance/amounts', 'AccountingRelatedSectionController@trialBalanceAmounts')->name('accounting.trial.balance.amounts');
        Route::get('cash/flow', 'AccountingRelatedSectionController@cashFow')->name('accounting.cash.flow');
        Route::get('all/cash/flow', 'AccountingRelatedSectionController@allCashflows')->name('accounting.all.cash.flow');
        Route::get('filter/cash/flow', 'AccountingRelatedSectionController@filterCashflows')->name('accounting.filter.cash.flow');
    });
});

Route::group(['prefix' => 'settings', 'namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'branches'], function () {
        Route::get('/', 'BranchController@index')->name('settings.branches.index');
        Route::get('get/all/branch', 'BranchController@getAllBranch')->name('settings.get.all.branch');
        Route::get('all/accounts', 'BranchController@getAllAccounts')->name('settings.get.all.accounts');
        Route::post('store', 'BranchController@store')->name('settings.branches.store');
        Route::post('update', 'BranchController@update')->name('settings.branches.update');
        Route::delete('delete', 'BranchController@delete')->name('settings.branches.delete');
        Route::get('all/schemas', 'BranchController@allSchemas')->name('settings.all.invoice.schemas');
        Route::get('all/layouts', 'BranchController@allLayouts')->name('settings.all.invoice.layouts');
    });

    Route::group(['prefix' => 'warehouses'], function () {
        Route::get('/', 'WarehouseController@index')->name('settings.warehouses.index');
        Route::get('get/all/warehuose', 'WarehouseController@getAllBranch')->name('settings.get.all.warehouse');
        Route::post('store', 'WarehouseController@store')->name('settings.warehouses.store');
        Route::post('update', 'WarehouseController@update')->name('settings.warehouses.update');
        Route::delete('delete/{warehouseId}', 'WarehouseController@delete')->name('settings.warehouses.delete');
    });

    Route::group(['prefix' => 'units'], function () {
        Route::get('/', 'UnitController@index')->name('settings.units.index');
        Route::get('get/all/unit', 'UnitController@getAllUnit')->name('settings.units.get.all.unit');
        Route::post('store', 'UnitController@store')->name('settings.units.store');
        Route::post('update', 'UnitController@update')->name('settings.units.update');
        Route::delete('delete/{unitId}', 'UnitController@delete')->name('settings.units.delete');
    });

    Route::group(['prefix' => 'taxes'], function () {
        Route::get('/', 'TaxController@index')->name('settings.taxes.index');
        Route::get('get/all/vat', 'TaxController@getAllVat')->name('settings.taxes.get.all.tax');
        Route::post('store', 'TaxController@store')->name('settings.taxes.store');
        Route::post('update', 'TaxController@update')->name('settings.taxes.update');
        Route::delete('delete/{taxId}', 'TaxController@delete')->name('settings.taxes.delete');
    });

    Route::group(['prefix' => 'general_settings'], function () {
        Route::get('/', 'GeneralSettingController@index')->name('settings.general.index');
        Route::post('business/settings', 'GeneralSettingController@businessSettings')->name('settings.business.settings');
        Route::post('tax/settings', 'GeneralSettingController@taxSettings')->name('settings.tax.settings');
        Route::post('product/settings', 'GeneralSettingController@productSettings')->name('settings.product.settings');
        Route::post('sale/settings', 'GeneralSettingController@saleSettings')->name('settings.sale.settings');
        Route::post('pos/settings', 'GeneralSettingController@posSettings')->name('settings.pos.settings');
        Route::post('purchase/settings', 'GeneralSettingController@purchaseSettings')->name('settings.purchase.settings');
        Route::post('dashboard/settings', 'GeneralSettingController@dashboardSettings')->name('settings.dashboard.settings');
        Route::post('prefix/settings', 'GeneralSettingController@prefixSettings')->name('settings.prefix.settings');
        Route::post('module/settings', 'GeneralSettingController@moduleSettings')->name('settings.module.settings');
        Route::post('email/settings', 'GeneralSettingController@emailSettings')->name('settings.email.settings');
    });

    Route::group(['prefix' => 'invoices'], function () {
        Route::group(['prefix' => 'schemas'], function () {
            Route::get('/', 'InvoiceSchemaController@index')->name('invoices.schemas.index');
            Route::post('store', 'InvoiceSchemaController@store')->name('invoices.schemas.store');
            Route::get('edit/{schemaId}', 'InvoiceSchemaController@edit')->name('invoices.schemas.edit');
            Route::post('update/{schemaId}', 'InvoiceSchemaController@update')->name('invoices.schemas.update');
            Route::delete('delete/{schemaId}', 'InvoiceSchemaController@delete')->name('invoices.schemas.delete');
            Route::get('set/default/{schemaId}', 'InvoiceSchemaController@setDefault')->name('invoices.schemas.set.default');
        });

        Route::group(['prefix' => 'layouts'], function () {
            Route::get('/', 'InvoiceLayoutController@index')->name('invoices.layouts.index');
            Route::get('create', 'InvoiceLayoutController@create')->name('invoices.layouts.create');
            Route::post('/', 'InvoiceLayoutController@store')->name('invoices.layouts.store');
            Route::get('edit/{layoutId}', 'InvoiceLayoutController@edit')->name('invoices.layouts.edit');
            Route::post('update/{layoutId}', 'InvoiceLayoutController@update')->name('invoices.layouts.update');
            Route::delete('delete/{layoutId}', 'InvoiceLayoutController@delete')->name('invoices.layouts.delete');
            Route::get('set/default/{schemaId}', 'InvoiceLayoutController@setDefault')->name('invoices.layouts.set.default');
        });
    });
});

Route::group(['prefix' => 'users',  'namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::get('all/users', 'UserController@allUsers')->name('users.all.Users');
    Route::get('create', 'UserController@create')->name('users.create');
    Route::get('all/roles', 'UserController@allRoles')->name('users.all.roles');
    Route::post('store', 'UserController@store')->name('users.store');
    Route::get('edit/{userId}', 'UserController@edit')->name('users.edit');
    Route::post('update/{userId}', 'UserController@update')->name('users.update');
    Route::delete('delete/{userId}', 'UserController@delete')->name('users.delete');
    Route::get('show/{userId}', 'UserController@show')->name('users.show');

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RoleController@index')->name('users.role.index');
        Route::get('all/roles', 'RoleController@allRoles')->name('users.role.all.roles');
        Route::get('create', 'RoleController@create')->name('users.role.create');
        Route::post('store', 'RoleController@store')->name('users.role.store');
        Route::get('edit/{roleId}', 'RoleController@edit')->name('users.role.edit');
        Route::post('update/{roleId}', 'RoleController@update')->name('users.role.update');
        Route::delete('delete/{roleId}', 'RoleController@delete')->name('users.role.delete');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', 'UserProfileController@index')->name('users.profile.index');
        Route::post('update', 'UserProfileController@update')->name('users.profile.update');
    });
});

Route::group(['prefix' => 'reports', 'namespace' => 'App\Http\Controllers\report'], function () {
    Route::group(['prefix' => 'profit/loss'], function () {
        Route::get('/', 'ProfitLossReportController@index')->name('reports.profit.loss.index');
        Route::get('sale/purchase/profit', 'ProfitLossReportController@salePurchaseProfit')->name('reports.profit.sale.purchase.profit');
        Route::get('filter/sale/purchase/profit/filter', 'ProfitLossReportController@filterSalePurchaseProfit')->name('reports.profit.filter.sale.purchase.profit');
        Route::get('by', 'ProfitLossReportController@profitBy');
    });

    Route::group(['prefix' => 'sales/purchase'], function () {
        Route::get('/', 'SalePurchaseReportController@index')->name('reports.sales.purchases.index');
        Route::get('sale/purchase/amounts', 'SalePurchaseReportController@salePurchaseAmounts')->name('reports.profit.sales.purchases.amounts');
        Route::get('filter/sale/purchase/amounts', 'SalePurchaseReportController@filterSalePurchaseAmounts')->name('reports.profit.sales.filter.purchases.amounts');
    });

    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('/', 'SupplierReportController@index')->name('reports.supplier.index');
    });

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/', 'CustomerReportController@index')->name('reports.customer.index');
    });

    Route::group(['prefix' => 'stock'], function () {
        Route::get('/', 'StockReportController@index')->name('reports.stock.index');
        Route::get('all/products', 'StockReportController@allProducts')->name('reports.stock.all.products');
        Route::get('filter/stock', 'StockReportController@filterStock')->name('reports.stock.filter');
        Route::get('all/parent/categories', 'StockReportController@allParentCategories')->name('reports.stock.all.parent.categories');
    });

    Route::group(['prefix' => 'stock/adjustments'], function () {
        Route::get('/', 'StockAdjustmentReportController@index')->name('reports.stock.adjustments.index');
        Route::get('all/adjustments', 'StockAdjustmentReportController@allAdjustments')->name('reports.stock.adjustments.all');
    });

    Route::group(['prefix' => 'trending/products'], function () {
        Route::get('/', 'TrendingProductReportController@index')->name('reports.trending.products.index');
        Route::get('tranding/product/list', 'TrendingProductReportController@trandingProductList')->name('reports.trending.product.list');
        Route::get('tranding/product/filter', 'TrendingProductReportController@trandingProductFilter')->name('reports.trending.product.filter');
    });

    Route::group(['prefix' => 'product/purchases'], function () {
        Route::get('/', 'ProductPurchaseReportController@index')->name('reports.product.purchases.index');
        Route::get('search/product/{product_name}', 'ProductPurchaseReportController@searchProduct');
    });

    Route::group(['prefix' => 'product/sales'], function () {
        Route::get('/', 'ProductSaleReportController@index')->name('reports.product.sales.index');
        Route::get('search/product/{product_name}', 'ProductSaleReportController@searchProduct');
    });

    Route::group(['prefix' => 'purchase/payments'], function () {
        Route::get('/', 'PurchasePaymentReportController@index')->name('reports.purchase.payments.index');
    });

    Route::group(['prefix' => 'sale/payments'], function () {
        Route::get('/', 'SalePaymentReportController@index')->name('reports.sale.payments.index');
        Route::get('get', 'SalePaymentReportController@getSalePaymentReport')->name('reports.get.sale.payments');
    });

    Route::group(['prefix' => 'expenses'], function () {
        Route::get('/', 'ExpanseReportController@index')->name('reports.expenses.index');
    });

    Route::group(['prefix' => 'cash/registers'], function () {
        Route::get('/', 'CashRegisterReportController@index')->name('reports.cash.registers.index');
        Route::get('get', 'CashRegisterReportController@getCashRegisterReport')->name('reports.get.cash.registers');
        Route::get('details/{cashRegisterId}', 'CashRegisterReportController@detailsCashRegister')->name('reports.get.cash.register.details');
    });

    Route::group(['prefix' => 'sale/representive'], function () {
        Route::get('/', 'SaleRepresentiveReportController@index')->name('reports.sale.representive.index');
        Route::get('expenses', 'SaleRepresentiveReportController@SaleRepresentiveExpenseReport')->name('reports.sale.representive.expenses');
    });

    Route::group(['prefix' => 'taxes'], function () {
        Route::get('/', 'TaxReportController@index')->name('reports.taxes.index');
        Route::get('get', 'TaxReportController@getTaxReport')->name('reports.taxes.get');
    });
});

Route::get('/', 'App\Http\Controllers\DashboardController@index')->name('dashboard.dashboard');
Route::get('change/lang/{lang}', 'App\Http\Controllers\DashboardController@changeLang')->name('change.lang');

Route::get('add-admin', function () {
    $addAdmin = new AdminAndUser();
    $addAdmin->prefix = 'Mr.';
    $addAdmin->name = 'Branch Manager';
    $addAdmin->email = 'branchmanager@gmail.com';
    $addAdmin->username = 'branchmanager';
    $addAdmin->password = Hash::make('12345');
    $addAdmin->role_type = 3;
    $addAdmin->role_id = 10;
    $addAdmin->role_permission_id = 7;
    $addAdmin->allow_login = 1;
    $addAdmin->save();
    //1=super_admin;2=admin;3=Other;
});


Route::get('/test', function () {
    // $bussinessSettings = General_setting::first();
    // return json_decode($bussinessSettings->business, true);
    // json_decode($bussinessSettings->business, true)['currency'];
    // return $dueInvoices = Sale::where('customer_id', 1)
    //                         ->where('status', 1)
    //                         ->where('due', '>', 0)
    //                         ->get();
    //return auth()->user()->permission->report;
    //return auth()->user()->role_type;
    // return $opening_stock = ProductOpeningStock::whereYear('created_at', date('Y'))->select('id', 'unit_cost_exc_tax', 'subtotal')->get();
    // return $sale_product = DB::table('sale_products')->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')->where('product_id', 3)->where('sales.branch_id', 10)->get();
    //return date('h:i', strtotime(date('6:28'). ' +1 hour'));
    //return Config::get('app.timezone');
    //return date('h:i:s a');
    //return bcadd(100.2, 0, 2);
    //return Hash::make('12345');
    DB::statement('create database new_inventory_2');
    Artisan::call('migrate');
});

Auth::routes();
