<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LoanCompanyController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\CommonAjaxCallController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ImportPriceGroupProductController;

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

        Route::group(['prefix' => 'import/price/group/products'], function () {

            Route::get('export', [ImportPriceGroupProductController::class, 'export'])->name('products.export.price.group.products');
        });
    });
});

Route::group(['prefix' => 'accounting'], function () {

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
