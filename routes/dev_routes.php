<?php

use Illuminate\Support\Arr;
use App\Models\Accounts\Account;
use App\Enums\DayBookVoucherType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use App\Models\Accounts\AccountGroup;
use App\Models\Products\ProductStock;
use Illuminate\Support\Facades\Route;
use App\Models\Accounts\AccountingVoucherDescription;

Route::get('my-test', function () {

    // return $accounts = Account::query()->with(['bank', 'bankAccessBranch'])
    //     ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
    //     ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
    //     ->get();

    // $str = 'Shop 1';
    // $exp = explode(' ', $str);

    // $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
    // $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

    // return $str1.$str2;
    // $str = 'DifferentShop';
    // return $str = preg_replace("/[A-Z]/", ' ' . "$0", $str);

    // return $accountGroups = Account::query()
    //     ->with([
    //         'bank:id,name',
    //         'group:id,sorting_number,sub_sub_group_number',
    //         'bankAccessBranch'
    //     ])
    //     ->where('branch_id', auth()->user()->branch_id)
    //     ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')

    //     ->whereIn('account_groups.sub_sub_group_number', [2])
    //     ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.bank_id', 'accounts.account_group_id')
    //     ->orWhereIn('account_groups.sub_sub_group_number', [1, 11])
    //     ->get();

    // return $receipts = AccountingVoucherDescription::query()
    //     ->with([
    //         'account:id,name,phone,address',
    //         'accountingVoucher:id,branch_id,voucher_no,date,date_ts,voucher_type,sale_ref_id,purchase_return_ref_id,stock_adjustment_ref_id,total_amount,remarks,created_by_id',
    //         'accountingVoucher.branch:id,name,branch_code,parent_branch_id',
    //         'accountingVoucher.branch.parentBranch:id,name',
    //         'accountingVoucher.voucherDebitDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
    //         'accountingVoucher.voucherDebitDescription.account:id,name',
    //         'accountingVoucher.voucherDebitDescription.paymentMethod:id,name',
    //         'accountingVoucher.createdBy:id,prefix,name,last_name',
    //         'accountingVoucher.saleRef:id,status,invoice_id,order_id',
    //         'accountingVoucher.purchaseReturnRef:id,voucher_no',
    //     ])
    //     ->where('amount_type', 'cr')
    //     ->where('account_id', 226)
    //     ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
    //     ->where('accounting_vouchers.voucher_type', AccountingVoucherType::Receipt->value)
    //     ->select(
    //         'accounting_voucher_descriptions.id as idf',
    //         'accounting_voucher_descriptions.accounting_voucher_id',
    //     )
    //     ->orderBy('accounting_vouchers.date_ts', 'desc')
    //     ->get();

    // foreach ($receipts as $receipt) {
    //     echo 'Date : ' . $receipt?->accountingVoucher->date . '</br>';
    //     echo 'Voucher No : ' . $receipt?->accountingVoucher->voucher_no . '</br>';
    //     echo 'Voucher Type : ' . ($receipt?->accountingVoucher->voucher_type == AccountingVoucherType::Receipt->value ? AccountingVoucherType::Receipt->name : '') . '</br>';
    //     echo 'Received Amount : ' . $receipt?->accountingVoucher->total_amount . '</br>';
    //     echo 'debit A/c : ' . $receipt?->accountingVoucher?->voucherDebitDescription?->account?->name . '</br>';
    //     echo 'Payment Method : ' . $receipt?->accountingVoucher?->voucherDebitDescription?->paymentMethod?->name . '</br>';
    //     echo 'Cheque No : ' . $receipt?->accountingVoucher?->voucherDebitDescription?->cheque_no . '</br>';
    //     echo 'Cheque Serial No : ' . $receipt?->accountingVoucher?->voucherDebitDescription?->cheque_serial_no . '</br>';
    //     echo 'Received From : ' . $receipt?->account?->name . '</br>';
    //     echo 'Remarks : ' . $receipt?->accountingVoucher?->remarks . '</br></br></br>';
    // }

    return $ownBranchStock = DB::table('product_ledgers')
    ->where('product_ledgers.branch_id', null)
    ->where('product_ledgers.product_id', 32)
    ->leftJoin('branches', 'product_ledgers.branch_id', 'branches.id')
    ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
    ->leftJoin('warehouses', 'product_ledgers.warehouse_id', 'warehouses.id')
        ->select(
            'branches.name',
            'parentBranch.name',
            'warehouses.warehouse_name',
            'warehouses.is_global',
            'product_ledgers.branch_id',
            'product_ledgers.warehouse_id',
            DB::raw('IFNULL(SUM(product_ledgers.in - product_ledgers.out), 0) as stock'),
            DB::raw('IFNULL(SUM(case when voucher_type = 0 then product_ledgers.in end), 0) as total_opening_stock'),
            DB::raw('IFNULL(SUM(case when voucher_type = 7 then product_ledgers.out end), 0) as total_transferred'),
            DB::raw('IFNULL(SUM(case when voucher_type = 8 then product_ledgers.in end), 0) as total_received'),
            DB::raw('IFNULL(SUM(case when voucher_type = 6 then product_ledgers.in end), 0) as total_production'),
            DB::raw('IFNULL(SUM(case when voucher_type = 6 then product_ledgers.out end), 0) as total_used_in_production'),
            DB::raw('IFNULL(SUM(case when voucher_type = 3 then product_ledgers.in end), 0) as total_purchase'),
            DB::raw('IFNULL(SUM(case when voucher_type = 4 then product_ledgers.out end), 0) as total_purchase_return'),
            DB::raw('IFNULL(SUM(case when voucher_type = 5 then product_ledgers.out end), 0) as total_stock_adjustment'),
            DB::raw('IFNULL(SUM(case when voucher_type = 2 then product_ledgers.in end), 0) as total_sales_return'),
            DB::raw('IFNULL(SUM(case when voucher_type = 1 then product_ledgers.out end), 0) as total_sale'),
            DB::raw("SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_cost"),
        )
        ->groupBy('product_ledgers.branch_id', 'product_ledgers.warehouse_id', 'product_ledgers.product_id', 'product_ledgers.variant_id');

});

Route::get('t-id', function () {
});
