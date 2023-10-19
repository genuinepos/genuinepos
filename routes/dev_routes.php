<?php

use App\Models\Account;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Models\Products\ProductStock;
use Illuminate\Support\Facades\Route;

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

    $productLedger = DB::table('product_ledgers')
        ->where('product_ledgers.product_id', 29)
        ->where('product_ledgers.variant_id', null)
        ->where('product_ledgers.branch_id', null)
        ->where('product_ledgers.warehouse_id', null)
        ->select(
            DB::raw("SUM(product_ledgers.in) as stock_in"),
            DB::raw("SUM(product_ledgers.out) as stock_out"),
            // DB::raw("SUM(case when purchase_product_id then product_ledgers.subtotal end) as total_purchased_cost"),
            DB::raw("SUM(product_ledgers.subtotal) as total_purchased_cost"),
        )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

    $currentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');

    $productStock = ProductStock::where('product_id', 29)
        ->where('variant_id', null)
        ->where('branch_id', null)
        ->where('branch_id', null)
        ->first();

    $avgUnitCost = $currentStock > 0 ? $productLedger->sum('total_purchased_cost') / $currentStock : $product->product_cost;
    $stockValue = $avgUnitCost * $currentStock;

    $productStock->stock = $currentStock;
    $productStock->stock_value = $stockValue;
    $productStock->save();

    return $productStock;
});

Route::get('t-id', function () {
});
