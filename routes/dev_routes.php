<?php

use App\Models\Setups\Branch;
use App\Models\Accounts\Account;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\Route;
use App\Services\Sales\SaleProductService;
use App\Models\Accounts\AccountingVoucherDescription;
use App\Models\Accounts\AccountingVoucherDescriptionReference;

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

    // $data = DB::table('voucher_description_references')
    // ->join('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
    // ->join('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
    // ->join('sales', 'voucher_description_references.sale_id', 'sales.id')

    return DB::table('sale_products')
            ->where('sale_products.sale_id', 156)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->select(
                'sale_products.product_id',
                'sale_products.variant_id',
                'sale_products.description',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_amount',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                // 'sale_products.subtotal',
                // 'sale_products.ex_status',
                'products.name as p_name',
                'products.product_code',
                'products.warranty_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'warranties.duration as w_duration',
                'warranties.duration_type as w_duration_type',
                'warranties.description as w_description',
                'warranties.type as w_type',
                'units.code_name as unit_code_name',
                DB::raw('SUM(sale_products.quantity) as quantity'),
                DB::raw('SUM(sale_products.subtotal) as subtotal'),
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.unit_tax_amount')
            // ->groupBy('sale_products.subtotal')
            // ->groupBy('sale_products.ex_status')
            ->groupBy('products.warranty_id')
            ->groupBy('products.name')
            ->groupBy('products.product_code')
            ->groupBy('warranties.duration')
            ->groupBy('warranties.duration_type')
            ->groupBy('warranties.type')
            ->groupBy('warranties.description')
            ->groupBy('product_variants.variant_name')
            ->groupBy('product_variants.variant_code')
            ->groupBy('units.code_name')
            ->get();
});

Route::get('t-id', function () {
});
