<?php

use App\Http\Controllers\GeneralSearch\GeneralProductSearchController;

Route::controller(GeneralProductSearchController::class)->prefix('general/product/search')->group(function () {

    Route::get('common/{keyword}/{isShowNotForSaleItem}/{priceGroup?}/{branchId?}', 'commonSearch')->name('general.product.search.common');
    Route::get('check/product/discount/{productId}/{priceGroupId}', 'checkProductDiscount')->name('general.product.search.check.product.discount');
    Route::get('check/product/discount/with/stock/{productId}/{variantId}/{priceGroupId}', 'checkProductDiscountWithStock')->name('general.product.search.check.product.discount.with.stock');
    Route::get('single/product/stock/{productId}/{warehouseId?}', 'singleProductStock')->name('general.product.search.single.product.stock');
    Route::get('variant/product/stock/{productId}/{variantId}/{warehouseId?}', 'variantProductStock')->name('general.product.search.variant.product.stock');
    Route::get('product/unit/and/multiplier/unit/{productId}', 'productUnitAndMultiplierUnit')->name('general.product.search.product.unit.and.multiplier.unit');
});
