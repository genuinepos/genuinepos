<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts\BankController;
use App\Http\Controllers\Accounts\ContraController;
use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Accounts\DayBookController;
use App\Http\Controllers\Accounts\ExpenseController;
use App\Http\Controllers\Accounts\JournalController;
use App\Http\Controllers\Accounts\PaymentController;
use App\Http\Controllers\Accounts\ReceiptController;
use App\Http\Controllers\Accounts\AccountGroupController;
use App\Http\Controllers\Accounts\AccountLedgerController;
use App\Http\Controllers\Accounts\AccountBalanceController;
use App\Http\Controllers\Accounts\CapitalAccountController;
use App\Http\Controllers\Accounts\Reports\CashFlowController;
use App\Http\Controllers\Accounts\DutyAndTaxAccountController;
use App\Http\Controllers\Accounts\Reports\TrialBalanceController;
use App\Http\Controllers\Accounts\Reports\VatTaxReportController;
use App\Http\Controllers\Accounts\Reports\DayBookReportController;
use App\Http\Controllers\Accounts\Reports\ExpenseReportController;
use App\Http\Controllers\Accounts\Reports\FinancialReportController;
use App\Http\Controllers\Accounts\Reports\ProfitLossReportController;

Route::group(['prefix' => 'accounting'], function () {

    Route::controller(BankController::class)->prefix('banks')->group(function () {

        Route::get('/', 'index')->name('banks.index');
        Route::get('create', 'create')->name('banks.create');
        Route::post('store', 'store')->name('banks.store');
        Route::get('edit/{id}', 'edit')->name('banks.edit');
        Route::post('update/{id}', 'update')->name('banks.update');
        Route::delete('delete/{id}', 'delete')->name('banks.delete');
    });

    Route::controller(AccountGroupController::class)->prefix('account-groups')->group(function () {

        Route::get('/', 'index')->name('account.groups.index');
        Route::get('list', 'groupList')->name('account.groups.list');
        Route::get('create', 'create')->name('account.groups.create');
        Route::post('store', 'store')->name('account.groups.store');
        Route::get('edit/{id}', 'edit')->name('account.groups.edit');
        Route::post('update/{id}', 'update')->name('account.groups.update');
        Route::delete('delete/{id}', 'delete')->name('account.groups.delete');
    });

    Route::controller(AccountController::class)->prefix('accounts')->group(function () {

        Route::get('/', 'index')->name('accounts.index');
        Route::get('create{type}', 'create')->name('accounts.create');
        Route::post('store', 'store')->name('accounts.store');
        Route::get('edit/{id}/{type}', 'edit')->name('accounts.edit');
        Route::post('update/{id}', 'update')->name('accounts.update');
        Route::delete('delete/{accountId}', 'delete')->name('accounts.delete');
        Route::get('expense/accounts', 'expenseAccounts')->name('accounts.expense.accounts');
        Route::get('customer/accounts', 'customerAccounts')->name('accounts.customer.accounts');

        Route::controller(CapitalAccountController::class)->prefix('capitals')->group(function () {

            Route::get('/', 'index')->name('accounts.capitals.index');
        });

        Route::controller(DutyAndTaxAccountController::class)->prefix('duties-and-taxes')->group(function () {

            Route::get('/', 'index')->name('accounts.duties.taxes.index');
        });

        Route::controller(AccountBalanceController::class)->prefix('balance')->group(function () {

            Route::get('account/balance/{accountId}', 'accountBalance')->name('accounts.balance');
        });

        Route::controller(AccountLedgerController::class)->prefix('ledger')->group(function () {

            Route::get('index/{id}/{fromDate?}/{toDate?}/{branchId?}', 'index')->name('accounts.ledger.index');
            Route::get('print/{id}', 'print')->name('accounts.ledger.print');
        });
    });

    Route::controller(ReceiptController::class)->prefix('receipts')->group(function () {

        Route::get('index/{creditAccountId?}', 'index')->name('receipts.index');
        Route::get('show/{id}', 'show')->name('receipts.show');
        Route::get('print/{id}', 'print')->name('receipts.print');
        Route::get('create/{creditAccountId?}', 'create')->name('receipts.create');
        Route::post('store', 'store')->name('receipts.store');
        Route::get('edit/{id}/{creditAccountId?}', 'edit')->name('receipts.edit');
        Route::post('update/{id}', 'update')->name('receipts.update');
        Route::delete('delete/{id}', 'delete')->name('receipts.delete');
    });

    Route::controller(PaymentController::class)->prefix('payments')->group(function () {

        Route::get('index/{debitAccountId?}', 'index')->name('payments.index');
        Route::get('show/{id}', 'show')->name('payments.show');
        Route::get('print/{id}', 'print')->name('payments.print');
        Route::get('create/{debitAccountId?}', 'create')->name('payments.create');
        Route::post('store', 'store')->name('payments.store');
        Route::get('edit/{id}/{debitAccountId?}', 'edit')->name('payments.edit');
        Route::post('update/{id}', 'update')->name('payments.update');
        Route::delete('delete/{id}', 'delete')->name('payments.delete');
    });

    Route::controller(ExpenseController::class)->prefix('expenses')->group(function () {

        Route::get('/', 'index')->name('expenses.index');
        Route::get('show/{id}', 'show')->name('expenses.show');
        Route::get('print/{id}', 'print')->name('expenses.print');
        Route::get('create', 'create')->name('expenses.create');
        Route::post('store', 'store')->name('expenses.store');
        Route::get('edit/{id}', 'edit')->name('expenses.edit');
        Route::post('update/{id}', 'update')->name('expenses.update');
        Route::delete('delete/{id}', 'delete')->name('expenses.delete');
    });

    Route::controller(ContraController::class)->prefix('contras')->group(function () {

        Route::get('/', 'index')->name('contras.index');
        Route::get('show/{id}', 'show')->name('contras.show');
        Route::get('print/{id}', 'print')->name('contras.print');
        Route::get('create', 'create')->name('contras.create');
        Route::post('store', 'store')->name('contras.store');
        Route::get('edit/{id}', 'edit')->name('contras.edit');
        Route::post('update/{id}', 'update')->name('contras.update');
        Route::delete('delete/{id}', 'delete')->name('contras.delete');
    });

    Route::controller(JournalController::class)->prefix('journals')->group(function () {

        Route::get('create', 'create')->name('journals.create');
        Route::get('search/account', 'searchAccount')->name('journals.search.account');
    });

    Route::controller(DayBookController::class)->prefix('day-books')->group(function () {

        Route::get('vouchers/for/receipts/or/payments/{accountId?}/{type?}', 'vouchersForReceiptOrPayment')->name('daybooks.vouchers.for.receipt.or.payment');
    });

    Route::group(['prefix' => 'reports'], function () {

        Route::controller(ProfitLossReportController::class)->prefix('profit-loss')->group(function () {

            Route::get('/', 'index')->name('reports.profit.loss.index');
            Route::get('amounts', 'profitLossAmounts')->name('reports.profit.loss.amounts');
            Route::get('print', 'printProfitLoss')->name('reports.profit.loss.print');
        });

        Route::controller(VatTaxReportController::class)->prefix('vat-tax')->group(function () {

            Route::get('/', 'index')->name('reports.vat.tax.index');
            Route::get('input/table', 'vatTaxInputTable')->name('reports.vat.tax.input.table');
            Route::get('output/table', 'vatTaxOutputTable')->name('reports.vat.tax.output.table');
            Route::get('vat/tax/amounts', 'vatTaxAmounts')->name('reports.vat.tax.amounts');
            Route::get('print', 'printVatTax')->name('reports.vat.tax.print');
        });

        Route::controller(FinancialReportController::class)->prefix('financial')->group(function () {

            Route::get('/', 'index')->name('reports.financial.index');
            Route::get('amounts', 'financialAmounts')->name('reports.financial.amounts');
            Route::get('report/print', 'print')->name('reports.financial.report.print');
        });

        Route::controller(DayBookReportController::class)->prefix('day-book')->group(function () {

            Route::get('/', 'index')->name('reports.day.book.index');
            Route::get('print', 'print')->name('reports.day.book.print');
        });

        Route::controller(CashFlowController::class)->prefix('cash-flow')->group(function () {

            Route::get('/', 'index')->name('reports.cash.flow.index');
            Route::get('data', 'cashFlowData')->name('reports.cash.flow.data');
            Route::get('print', 'cashFlowPrint')->name('reports.cash.flow.print');
        });

        Route::controller(TrialBalanceController::class)->prefix('trial-balance')->group(function () {

            Route::get('/', 'index')->name('reports.trial.balance.index');
            Route::get('data', 'trialBalanceData')->name('reports.trial.balance.data');
            Route::get('print', 'trialBalancePrint')->name('reports.trial.balance.print');
        });

        Route::controller(ExpenseReportController::class)->prefix('expenses')->group(function () {

            Route::get('/', 'index')->name('reports.expenses.index');
            Route::get('print', 'print')->name('reports.expenses.print');
        });
    });
});
