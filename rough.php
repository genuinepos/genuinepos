<?php

$receipts = DB::table('accounting_vouchers')->where('voucher_type', AccountingVoucherType::Receipt->value)
    ->leftJoin('accounting_voucher_descriptions', 'accounting_vouchers.id', 'accounting_voucher_descriptions.accounting_voucher_id')
    ->where('accounting_voucher_descriptions.account_id', 35)
    ->delete();

$accountLedger = DB::table('account_ledgers')->where('account_id', 35)->whereNotNull('sale_id')->delete();

$sales = Sale::where('customer_account_id', 35)->get();

$accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
$accountingVoucherService = new \App\Services\Accounts\AccountingVoucherService();
$accountingVoucherDescriptionService = new \App\Services\Accounts\AccountingVoucherDescriptionService();
$accountingVoucherDescriptionReferenceService = new \App\Services\Accounts\AccountingVoucherDescriptionReferenceService();
$dayBookService = new \App\Services\Accounts\DayBookService();

$codeGenerator = new \App\Services\CodeGenerationService();

$generalSettings = config('generalSettings');
$receiptVoucherPrefix = $generalSettings['prefix__receipt_voucher_prefix'] ? $generalSettings['prefix__receipt_voucher_prefix'] : 'RV';

foreach ($sales as $sale) {

    $saleDate = $sale->date;
    $saleTime = date(' H:i:s', strtotime($sale->sale_date_ts));
    $invoiceAmount = $sale->total_invoice_amount;

    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Sales->value, account_id: 35, date: $saleDate, trans_id: $sale->id, amount: $invoiceAmount, amount_type: 'debit', temporary_time: $saleTime);

    $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $saleDate, voucherType: AccountingVoucherType::Receipt->value, remarks: null, codeGenerator: $codeGenerator, voucherPrefix: $receiptVoucherPrefix, debitTotal: $sale->total_invoice_amount, creditTotal: $invoiceAmount, totalAmount: $invoiceAmount, saleRefId: $sale->id);

    // Add Debit Account Accounting voucher Description
    $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 26, paymentMethodId: 1, amountType: 'dr', amount: $invoiceAmount);

    //Add Debit Ledger Entry
    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $saleDate, account_id: 26, trans_id: $addAccountingVoucherDebitDescription->id, amount: $invoiceAmount, amount_type: 'debit', temporary_time: $saleTime);

    // Add Payment Description Credit Entry
    $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 35, paymentMethodId: null, amountType: 'cr', amount: $invoiceAmount, note: null);

    // Add Accounting VoucherDescription References
    $accountingVoucherDescriptionReferenceService->addAccountingVoucherDescriptionReferences(accountingVoucherDescriptionId: $addAccountingVoucherCreditDescription->id, accountId: 35, amount: $invoiceAmount, refIdColName: 'sale_id', refIds: [$sale->id]);

    // Add Day Book entry for Receipt
    $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Receipt->value, date: $saleDate, accountId: 35, transId: $addAccountingVoucherCreditDescription->id, amount: $invoiceAmount, amountType: 'credit', temporaryTime: $saleTime);

    //Add Credit Ledger Entry
    $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Receipt->value, date: $saleDate, account_id: 35, trans_id: $addAccountingVoucherCreditDescription->id, amount: $invoiceAmount, amount_type: 'credit', cash_bank_account_id: 26, temporary_time: $saleTime);
}

$accountingVouchers = $accountingVoucherService->accountingVouchers()->orderBy('date_ts', 'asc')->get();

foreach ($accountingVouchers as $accountingVoucher) {
    $accountingVoucher->voucher_no = null;
    $accountingVoucher->save();
}

$accountingVouchers = $accountingVoucherService->accountingVouchers()->orderBy('date_ts', 'asc')->get();
foreach ($accountingVouchers as $accountingVoucher) {

    $dateTimePrefix = date('ym', strtotime($accountingVoucher->date_ts));

    $intVal = date('m', strtotime($accountingVoucher->date_ts));

    $voucherNo = $codeGenerator->generateMonthAndTypeWise(table: 'accounting_vouchers', column: 'voucher_no', typeColName: 'voucher_type', typeValue: $accountingVoucher->voucher_type, prefix: $receiptVoucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id, dateTimePrefix: $dateTimePrefix, intVal: $intVal);

    $accountingVoucher->voucher_no = $voucherNo;
    $accountingVoucher->save();
}

$ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

$customers = Contact::where('type', ContactType::Customer->value)->get();
foreach ($customers as $customer) {

    $customer->branch_id = $ownBranchIdOrParentBranchId;
    $customer->contact_id = null;
    $customer->prefix = null;
    $customer->save();
}

foreach ($customers as $customer) {

    $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
    $generalSettings = config('generalSettings');
    $cusIdPrefix = $generalSettings['prefix__customer_id'] ? $generalSettings['prefix__customer_id'] : 'C';
    $isCheckBranch = true;
    $contactId = $codeGenerator->generateAndTypeWiseWithoutYearMonth(table: 'contacts', column: 'contact_id', typeColName: 'type', typeValue: ContactType::Customer->value, prefix: $cusIdPrefix, digits: 4, isCheckBranch: $isCheckBranch, branchId: $ownBranchIdOrParentBranchId);

    $prefixTypeSign = 'C';
    $contactPrefix = $codeGenerator->generateAndTypeWiseWithoutYearMonth(table: 'contacts', column: 'contact_id', typeColName: 'type', typeValue: ContactType::Customer->value, prefix: $prefixTypeSign, digits: 0, splitter: ':', isCheckBranch: $isCheckBranch, branchId: $ownBranchIdOrParentBranchId);

    $customer->branch_id = $ownBranchIdOrParentBranchId;
    $customer->contact_id = $contactId;
    $customer->prefix = $contactPrefix;
    $customer->save();
}


function addExpenses()
{
    // Add Expenses
    $directExpenseGroup = DB::table('account_groups')->where('sub_group_number', 10)->first();
    $dbExpenses = DB::connection('home_care')->table('expanses')->get();
    foreach ($dbExpenses as $dbExpense) {

        $existsExpense = DB::table('accounting_vouchers')->where('voucher_type', AccountingVoucherType::Expense->value)->where('date_ts', $dbExpense->report_date)->first();

        if (!isset($existsExpense)) {
            // Add Accounting Voucher
            $expenseVoucherPrefix = $generalSettings['prefix__expense_voucher_prefix'] ? $generalSettings['prefix__expense_voucher_prefix'] : 'EV';

            $addAccountingVoucher = $accountingVoucherService->addAccountingVoucher(date: $dbExpense->date, voucherType: AccountingVoucherType::Expense->value, remarks: null, reference: null, codeGenerator: $codeGenerator, voucherPrefix: $expenseVoucherPrefix, debitTotal: $request->net_total_amount, creditTotal: $request->net_total_amount, totalAmount: $request->net_total_amount);

            $dbExpenseDescriptions = $dbExpenses = DB::connection('home_care')->table('expense_descriptions')->where('expense_id', $dbExpense->id)
                ->leftJoin('expanse_categories', 'expense_descriptions.expense_category_id', 'expanse_categories.id')
                ->select('expense_descriptions.amount','expanse_categories.name as expense_category_name')
                ->get();

            foreach ($dbExpenseDescriptions as $index => $dbExpenseDescription) {

                $existsExpenseAccount = DB::table('accounts')->where('accounts.name', $dbExpenseDescription->expense_category_name)->where('accounts.branch_id', auth()->user()->branch_id)->first();
                // 25
                $expenseAccountId = isset($existsExpenseAccount) ? $existsExpenseAccount->id : null;
                if (!isset($existsAccount)) {

                    $addAccount = $accountService->addAccount(
                        name: $dbExpenseDescription->expense_category_name,
                        accountGroup: $directExpenseGroup,
                    );

                    $expenseAccountId = $addAccount->id;
                }

                // Add Expense Description Debit Entry
                $addAccountingVoucherDebitDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: $expenseAccountId, paymentMethodId: null, amountType: 'dr', amount: $dbExpenseDescription->amount);

                if ($index == 0) {

                    $dayBookService->addDayBook(voucherTypeId: DayBookVoucherType::Expense->value, date: $addAccountingVoucher->date, accountId: $expenseAccountId, transId: $addAccountingVoucherDebitDescription->id, amount: $addAccountingVoucher->total_amount, amountType: 'debit');
                }

                //Add Debit Ledger Entry
                $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $addAccountingVoucher->date, account_id: $expenseAccountId, trans_id: $addAccountingVoucherDebitDescription->id, amount: $dbExpenseDescription->amount, amount_type: 'debit', cash_bank_account_id: 14);
            }

            // Add Credit Account Accounting voucher Description
            $addAccountingVoucherCreditDescription = $accountingVoucherDescriptionService->addAccountingVoucherDescription(accountingVoucherId: $addAccountingVoucher->id, accountId: 14, paymentMethodId: 1, amountType: 'cr', amount: $addAccountingVoucher->total_amount, transactionNo: null, chequeNo: null, chequeSerialNo: null);

            //Add Credit Ledger Entry
            $accountLedgerService->addAccountLedgerEntry(voucher_type_id: AccountLedgerVoucherType::Expense->value, date: $addAccountingVoucher->date, account_id: 14, trans_id: $addAccountingVoucherCreditDescription->id, amount: $addAccountingVoucher->total_amount, amount_type: 'credit');
        }
    }
}
