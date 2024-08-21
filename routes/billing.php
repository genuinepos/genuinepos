<?php

use App\Http\Controllers\Billing\AddShopController;
use App\Http\Controllers\Billing\ShopRenewController;
use App\Http\Controllers\Billing\AddBusinessController;
use App\Http\Controllers\Billing\UpgradePlanController;
use App\Http\Controllers\Billing\DueRepaymentController;
use App\Http\Controllers\Billing\CheckCouponCodeController;
use App\Http\Controllers\Billing\SoftwareServiceBillingController;

Route::controller(SoftwareServiceBillingController::class)->prefix('billing')->group(function () {

    Route::get('/', 'index')->name('software.service.billing.index');
    Route::get('invoice/view/{id}', 'invoiceView')->name('software.service.billing.invoice.view');
    Route::get('invoice/pdf/{id}', 'invoicePdf')->name('software.service.billing.invoice.pdf');

    Route::controller(CheckCouponCodeController::class)->prefix('check-coupon-code')->group(function () {
        Route::get('/', 'checkCouponCode')->name('check.coupon.code');
    });

    Route::controller(UpgradePlanController::class)->prefix('upgrade-plan')->group(function () {

        Route::get('/', 'index')->name('software.service.billing.upgrade.plan.index');
        Route::get('cart/{id}/{pricePeriod?}', 'cart')->name('software.service.billing.upgrade.plan.cart');
        Route::post('confirm', 'confirm')->name('software.service.billing.upgrade.plan.confirm');
    });

    Route::controller(AddShopController::class)->prefix('add-shop')->group(function () {

        Route::get('cart', 'cart')->name('software.service.billing.add.shop.cart');
        Route::post('confirm', 'confirm')->name('software.service.billing.add.shop.confirm');
    });

    Route::controller(AddBusinessController::class)->prefix('add-business')->group(function () {

        Route::get('cart', 'cart')->name('software.service.billing.add.business.cart');
        Route::post('confirm', 'confirm')->name('software.service.billing.add.business.confirm');
    });

    Route::controller(ShopRenewController::class)->prefix('shop-renew')->group(function () {

        Route::get('cart', 'cart')->name('software.service.billing.shop.renew.cart');
        Route::post('confirm', 'confirm')->name('software.service.billing.shop.renew.confirm');
    });

    Route::controller(DueRepaymentController::class)->prefix('due-repayment')->group(function () {

        Route::get('/', 'index')->name('software.service.billing.due.repayment.index');
        Route::post('confirm', 'confirm')->name('software.service.billing.due.repayment.confirm');
    });
});
