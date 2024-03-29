<?php

use Carbon\Carbon;
use App\Models\Tenant;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Setups\Branch;
use App\Models\GeneralSetting;
use Modules\SAAS\Entities\Plan;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\Route;
use App\Models\Accounts\AccountLedger;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountLedgerVoucherType;
use Illuminate\Support\Facades\Session;
use App\Models\Subscriptions\Subscription;
use App\Models\Accounts\AccountingVoucherDescription;
use Illuminate\Support\Facades\File;

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

    // $ownBranchIdOrParentBranchId = auth()?->user()?->branch?->parent_branch_id ? auth()?->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

    // $branchId = auth()->user()->branch_id;

    // return DB::table('products')
    //     ->leftJoin('units', 'products.unit_id', 'units.id')
    //     ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
    //     ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
    //     ->leftJoin('branches', 'product_access_branches.branch_id', 'branches.id')
    //     ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
    //     ->leftJoin('product_stocks', 'products.id', 'product_stocks.product_id')
    //     ->where('product_access_branches.branch_id', $ownBranchIdOrParentBranchId)
    //     ->select(
    //         'products.name as product_name',
    //         'products.product_code',
    //         'units.name as unit_name',
    //         'product_variants.variant_name',
    //         'product_variants.variant_code',
    //         'branches.name as branch_name',
    //         'branches.branch_code',
    //         'parentBranch.name as parent_branch_name',
    //         DB::raw('SUM(CASE WHEN product_stocks.branch_id is null AND product_stocks.warehouse_id is null THEN product_stocks.stock END) as current_stock')
    //     )
    //     ->distinct('products.id')
    //     // ->distinct('product_access_branches.branch_id')
    //     ->orderBy('products.name', 'asc')
    //     ->groupBy(
    //         'products.name',
    //         'products.product_code',
    //         'units.name',
    //         'product_variants.variant_name',
    //         'product_variants.variant_code',
    //         'branches.id',
    //         'branches.name',
    //         'branches.branch_code',
    //         'parentBranch.name',
    //         'product_stocks.product_id',
    //         'product_stocks.variant_id',
    //     )
    //     ->get();

    // $settings['Rp_poins_sett']; // Bata parent branch -> 10% <--- Fallback and get 10% set Bata Uttara branch (Special) -> 11%
    // parent_branch_id === null  -> get it, parent_branch_id == 28 -> Get setting from parent

    //MONTH WISE DATES
    // $month = 11;
    // $year = 2023;
    // // start with empty results
    // // $resultDate = "";
    // // $resultDays = "";
    // $dates = [];
    // $datesAndDay = [];
    // // determine the number of days in the month
    // $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    // for ($i = 1; $i <= $daysInMonth; $i++) {
    //     // create a cell for the day and for the date
    //     // $resultDate .= "<td>" . sprintf('%02d', $i) . "</td>";
    //     // $resultDays .= "<td>" . date("l", mktime(0, 0, 0, $month, $i, $year)) . "</td>";
    //     array_push($datesAndDay, date("d D", mktime(0, 0, 0, $month, $i, $year)));
    //     array_push($dates, date('Y-m-d', strtotime($i . '-' . $month . '-' . $year)));
    //     // $fullDays[] = $i.'-'.$month.'-'.$year;
    // }

    // return $datesAndDay;

    // return the result wrapped in a table
    // return "<table>" . PHP_EOL .
    //     "<tr>" . $resultDate . "</tr>" . PHP_EOL .
    //     "<tr>" . $resultDays . "</tr>" . PHP_EOL .
    //     "</table>";

    // return DB::table('users')->where('users.id', 1)
    //     ->leftJoin('hrm_attendances', 'users.id', 'hrm_attendances.user_id')
    //     ->whereIn(DB::raw('DATE(hrm_attendances.clock_in_ts)'), $dates)
    //     ->get();
    //MONTH WISE DATES END

    //TEST
    // $currentMonth = Carbon\Carbon::now()->month;
    // return $results = App\Models\HRM\Holiday::whereYear('start_date', Carbon\Carbon::now()->year)
    //     ->whereMonth('start_date', '<=', $currentMonth) // Start date month should be less than or equal to current month
    //     ->whereYear('end_date', Carbon\Carbon::now()->year)
    //     ->whereMonth('end_date', '>=', $currentMonth)   // End date month should be greater than or equal to current month
    //     ->get();
    //TEST END

    //DATE RANGE WISE DATES

    // $first = '2023-12-16';
    // $last = '2023-12-16';
    // $step = '+1 day';
    // $output_format = 'Y-m-d';
    // $dates = array();
    // $current = strtotime($first);
    // $last = strtotime($last);

    // while($current <= $last) {

    //     $dates[] = date($output_format, $current);
    //     $current = strtotime($step, $current);
    // }

    // return $dates;

    // $str = 'C-000050';
    // return intval($str);

    // return request()->generalSettings['business_or_shop__business_name'];

    // $name = "Mr. John Doe";

    // // Remove Mr. and any spaces, then convert to lowercase
    // $username = strtolower(str_replace(' ', '', str_replace('.', '', $name)));

    // return $username; // Output: mrjohndoe

    // return App\Models\User::where('username', 'superadmin1')
    //     ->where('allow_login', 1)
    //     ->orWhere(function ($query) {
    //         $query->where('email', 'superadmin@email.com');
    //     })
    //     ->first();

    $startDate = new DateTime('2024-02-08');
    $endDate = clone $startDate;
    // Add 7 days to today's date
    $lastDate = $endDate->modify('+1 years');
    $lastDate = $lastDate->modify('+1 days');

    // Format the date
    // return $lastDate->format('Y-m-d');

    // return DB::table('subscriptions')
    // ->leftJoin('pos.plans', 'subscriptions.plan_id', 'pos.plans.id')
    // ->select('subscriptions.id', 'pos.plans.name as plan_name')
    // ->first();

    // return Subscription::with('plan')->first();
    // $timestamp = Carbon::parse($timestamp)->timezone('America/New_York')->format('Y-m-d H:i:s');
    // return $timestamp = Carbon::parse(date('Y-m-d H:i:s'))->timezone('Asia/Dhaka')->format('Y-m-d H:i:s A');
    // File::cleanDirectory(storage_path('framework/laravel-excel'));
    // return storage_path('framework/laravel-excel');

    $myArr = [
        'name' => 'Mr. X',
        'age' => 30,
    ];

    // return gettype($myArr);
    // $arr = (object) $myArr;
    // return $arr->name;
    
    function transactionDetails($requ)
    {
        return [
            'upgrade_plan_from_trial' => [
                'has_business' => '',
                'business_price_period' => '',
                'business_price_period_count' => '',
                'business_price' => '',
                'adjustable_business_price' => '',
                'business_subtotal' => '',
                'shop_count' => '',
                'shop_price_period' => '',
                'shop_price_period_count' => '',
                'shop_price' => '',
                'adjustable_shop_price' => '',
                'shop_subtotal' => '',
                'net_total' => '',
                'discount' => '',
                'total_amount' => '',
            ],
            'direct_buy_plan' => [
                'has_business' => '',
                'business_price_period' => '',
                'business_price_period_count' => '',
                'business_price' => '',
                'adjustable_business_price' => '',
                'business_subtotal' => '',
                'shop_count' => '',
                'shop_price_period' => '',
                'shop_price_period_count' => '',
                'shop_price' => '',
                'adjustable_shop_price' => '',
                'shop_subtotal' => '',
                'net_total' => '',
                'discount' => '',
                'total_amount' => '',
            ],
            'upgrade_plan_from_real_plan' => [
                'net_total' => '',
                'total_adjusted_amount' => '',
                'discount' => '',
                'total_amount' => '',
            ],
        ];
    }

    return transactionDetails()['upgrade_plan_from_trial'];
});



Route::get('t-id', function () {
});
