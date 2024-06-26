<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\UnitController;
use App\Http\Controllers\Products\BrandController;
use App\Http\Controllers\Products\BarcodeController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\WarrantyController;
use App\Http\Controllers\Products\PriceGroupController;
use App\Http\Controllers\Products\StockIssueController;
use App\Http\Controllers\Products\BulkVariantController;
use App\Http\Controllers\Products\SubCategoryController;
use App\Http\Controllers\Products\OpeningStockController;
use App\Http\Controllers\Products\ProductStockController;
use App\Http\Controllers\Products\ProductImportController;
use App\Http\Controllers\Products\ProductLedgerController;
use App\Http\Controllers\Products\ExpiredProductController;
use App\Http\Controllers\Products\QuickProductAddController;
use App\Http\Controllers\Products\PriceGroupManageController;
use App\Http\Controllers\Products\StockIssueProductController;
use App\Http\Controllers\Products\Reports\StockReportController;
use App\Http\Controllers\Products\Reports\StockInOutReportController;

Route::controller(ProductController::class)->prefix('products')->group(function () {

    Route::get('index/{isForCreatePage?}', 'index')->name('products.index');
    Route::get('show/{id}', 'show')->name('products.show');
    Route::get('create/{id?}', 'create')->name('products.create');
    Route::post('store', 'store')->name('products.store');
    Route::get('edit/{id}', 'edit')->name('products.edit');
    Route::post('update/{id}', 'update')->name('products.update');
    Route::get('changes/status/{id}', 'changeStatus')->name('products.change.status');
    Route::delete('delete/{id}', 'delete')->name('products.delete');
    Route::get('form/part/{type}', 'formPart')->name('products.form.part');
    Route::get('get/last/product/id', 'getLastProductId')->name('products.get.last.product.id');

    Route::controller(ProductLedgerController::class)->prefix('ledger')->group(function () {
        Route::get('index/{id}', 'index')->name('products.ledger.index');
        Route::get('print/{id}', 'print')->name('products.ledger.print');
    });

    Route::controller(ProductStockController::class)->prefix('stock')->group(function () {

        Route::get('product/stock/{id}', 'productStock')->name('products.stock');
    });

    Route::controller(OpeningStockController::class)->prefix('opening-stock')->group(function () {
        Route::get('create/or/edit/{productId}', 'createOrEdit')->name('product.opening.stocks.create');
        Route::post('store/or/update', 'storeOrUpdate')->name('product.opening.stocks.store.or.update');
    });

    Route::controller(QuickProductAddController::class)->prefix('quick-product-add')->group(function () {
        Route::get('create', 'create')->name('quick.product.create');
        Route::post('store', 'store')->name('quick.product.store');
    });

    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::get('create', 'create')->name('categories.create');
        Route::post('store', 'store')->name('categories.store');
        Route::get('edit/{id}', 'edit')->name('categories.edit');
        Route::post('update/{id}', 'update')->name('categories.update');
        Route::delete('delete/{id}', 'delete')->name('categories.delete');
    });

    Route::controller(SubCategoryController::class)->prefix('subcategories')->group(function () {
        Route::get('/', 'index')->name('subcategories.index');
        Route::get('create/{fixedParentCategoryId?}', 'create')->name('subcategories.create');
        Route::post('store', 'store')->name('subcategories.store');
        Route::get('edit/{id}', 'edit')->name('subcategories.edit');
        Route::post('update/{id}', 'update')->name('subcategories.update');
        Route::delete('delete/{id}', 'delete')->name('subcategories.delete');
        Route::get('subcategories/by/categoryId/{categoryId}', 'subcategoriesByCategoryId')->name('subcategories.by.category.id');
    });

    Route::controller(UnitController::class)->prefix('units')->group(function () {
        Route::get('/', 'index')->name('units.index');
        Route::get('create/{isAllowedMultipleUnit?}', 'create')->name('units.create');
        Route::post('store', 'store')->name('units.store');
        Route::get('edit/{id}', 'edit')->name('units.edit');
        Route::post('update/{id}', 'update')->name('units.update');
        Route::delete('delete/{id}', 'delete')->name('units.delete');
    });

    Route::controller(BulkVariantController::class)->prefix('bulk-variants')->group(function () {

        Route::get('/', 'index')->name('product.bulk.variants.index');
        Route::get('create', 'create')->name('product.bulk.variants.create');
        Route::post('store', 'store')->name('product.bulk.variants.store');
        Route::get('edit/{id}', 'edit')->name('product.bulk.variants.edit');
        Route::post('update/{id}', 'update')->name('product.bulk.variants.update');
        Route::delete('delete/{id}', 'delete')->name('product.bulk.variants.delete');
    });

    Route::controller(BrandController::class)->prefix('brands')->group(function () {
        Route::get('/', 'index')->name('brands.index');
        Route::get('create', 'create')->name('brands.create');
        Route::post('store', 'store')->name('brands.store');
        Route::get('edit/{id}', 'edit')->name('brands.edit');
        Route::post('update/{id}', 'update')->name('brands.update');
        Route::delete('delete/{id}', 'delete')->name('brands.delete');
    });

    Route::controller(WarrantyController::class)->prefix('warranties')->group(function () {
        Route::get('/', 'index')->name('warranties.index');
        Route::get('create', 'create')->name('warranties.create');
        Route::post('store', 'store')->name('warranties.store');
        Route::get('edit/{id}', 'edit')->name('warranties.edit');
        Route::post('update/{id}', 'update')->name('warranties.update');
        Route::delete('delete/{id}', 'delete')->name('warranties.delete');
    });

    Route::controller(PriceGroupController::class)->prefix('selling-price-groups')->group(function () {
        Route::get('/', 'index')->name('selling.price.groups.index');
        Route::get('create', 'create')->name('selling.price.groups.create');
        Route::post('store', 'store')->name('selling.price.groups.store');
        Route::get('edit/{id}', 'edit')->name('selling.price.groups.edit');
        Route::post('update/{id}', 'update')->name('selling.price.groups.update');
        Route::delete('delete/{id}', 'delete')->name('selling.price.groups.delete');
        Route::get('change/status/{id}', 'changeStatus')->name('selling.price.groups.change.status');

        Route::controller(PriceGroupManageController::class)->prefix('manage')->group(function () {
            Route::get('index/{productId}/{type}', 'index')->name('selling.price.groups.manage.index');
            Route::post('store/or/update', 'storeOrUpdate')->name('selling.price.groups.manage.store.or.update');
        });
    });

    Route::controller(StockIssueController::class)->prefix('stock-issues')->group(function () {

        Route::get('/', 'index')->name('stock.issues.index');
        Route::get('show/{id}', 'show')->name('stock.issues.show');
        Route::get('print/{id}', 'print')->name('stock.issues.print');
        Route::get('create', 'create')->name('stock.issues.create');
        Route::post('store', 'store')->name('stock.issues.store');
        Route::get('edit/{id}', 'edit')->name('stock.issues.edit');
        Route::post('update/{id}', 'update')->name('stock.issues.update');
        Route::delete('delete/{id}', 'delete')->name('stock.issues.delete');

        Route::controller(StockIssueProductController::class)->prefix('stock-issued-products')->group(function () {
            Route::get('/', 'index')->name('stock.issued.products.index');
        });
    });

    Route::controller(ExpiredProductController::class)->prefix('expired')->group(function () {
        Route::get('/', 'index')->name('expired.products.index');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(StockReportController::class)->prefix('product-stock')->group(function () {

            Route::get('/', 'index')->name('reports.stock.index');
            Route::get('branch/stocks', 'branchStock')->name('reports.product.stock.branch.stock');
            Route::get('branch/stocks/print', 'branchStockPrint')->name('reports.product.stock.branch.stock.print');
            Route::get('warehouse/stock', 'warehouseStock')->name('reports.product.stock.warehouse.stock');
            Route::get('warehouse/stock/print', 'warehouseStockPrint')->name('reports.product.stock.warehouse.stock.print');
        });

        Route::controller(StockInOutReportController::class)->prefix('stock-in-out')->group(function () {

            Route::get('/', 'index')->name('reports.stock.in.out.index');
            Route::get('print', 'print')->name('reports.stock.in.out.print');
        });
    });

    Route::controller(BarcodeController::class)->prefix('generate-barcode')->group(function () {

        Route::get('/', 'index')->name('barcode.index');
        Route::get('preview', 'preview')->name('barcode.preview');
        Route::post('empty/label/qty/{supplierAccountId}/{productId}/{variantId?}', 'emptyLabelQty')->name('barcode.empty.label.qty');
    });

    Route::controller(ProductImportController::class)->prefix('import')->group(function () {

        Route::get('create', 'create')->name('product.import.create');
        Route::post('store', 'store')->name('product.import.store');
    });
});
