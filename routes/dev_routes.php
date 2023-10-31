<?php

use Illuminate\Support\Arr;
use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Models\Products\Product;
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
});

Route::get('t-id', function () {
});
