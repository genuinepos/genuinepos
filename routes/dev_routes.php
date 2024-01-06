<?php

use App\Models\Setups\Branch;
use App\Models\GeneralSetting;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
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

    // $date = date('Y-11-01');
    // return $afterDate = date('Y-m-d', strtotime(' + 1 year - 1 day', strtotime($date)));

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

    $generalSettings =  GeneralSetting::where('branch_id', 39)->orWhereIn('key', [
        'addons__hrm',
        'addons__manage_task',
        'addons__service',
        'addons__manufacturing',
        'addons__e_commerce',
        'addons__branch_limit',
        'addons__cash_counter_limit',
        'business_or_shop__business_name'
    ])->pluck('value', 'key');

    // $parentBranchId = 38;
    // if (isset($parentBranchId)) {
    //     $query->addSelect([
    //         DB::raw("(CASE
    //                     WHEN branch_id = $parentBranchId AND key = business_or_shop__financial_year_start_month
    //                     THEN value -- Assuming 'value' is the column containing the financial year value
    //                     ELSE NULL
    //                 END) AS business_or_shop__financial_year_start_month")
    //     ]);
    // }
    $prefixes = [
        'business_or_shop__',
        'reward_point_settings__',
        'send_email__',
        'send_sms__',
    ];

    $query = GeneralSetting::query()->where('branch_id', 38)
    ->whereNotIn('key',
        [
            'business_or_shop__currency_id',
            'business_or_shop__currency_symbol',
            'business_or_shop__date_format',
            'business_or_shop__time_format',
            'business_or_shop__timezone',
            'business_or_shop__currency_symbol',
            'business_or_shop__currency_symbol',
        ]
    );

    $query->where(function ($query) use ($prefixes) {
        foreach ($prefixes as $prefix) {
            $query->orWhere('key', 'LIKE', $prefix . '%');
        }
    });

    $parentBranchGeneralSettings = $query->get();
    foreach ($parentBranchGeneralSettings as $parentBranchGeneralSetting) {
        $generalSettings[$parentBranchGeneralSetting->key] = $parentBranchGeneralSetting->value;
    }

    return $generalSettings;
});



Route::get('t-id', function () {
});
