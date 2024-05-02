<?php

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use App\Models\Setups\Branch;
use App\Models\GeneralSetting;
use Modules\SAAS\Entities\Plan;
use App\Models\Accounts\Account;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;
use App\Enums\AccountingVoucherType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Accounts\AccountLedger;
use Illuminate\Support\Facades\Schema;
use App\Enums\AccountLedgerVoucherType;
use Illuminate\Support\Facades\Session;
use App\Models\Subscriptions\Subscription;
use App\Models\Accounts\AccountingVoucherDescription;
use App\Models\ShortMenus\ShortMenu;

Route::get('my-test', function () {
    $filteredBranchId5 = 'NULL';

    $generalSettings = config('generalSettings');
    $accounts = '';
    $query = DB::table('accounts')
        ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
        ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
        ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
        ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
        ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
        ->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId5) {

            $__filteredBranchId1 = $filteredBranchId5 == 'NULL' ? null : $filteredBranchId5;
            $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                ->where('bank_access_branches.branch_id', 4);
        })
        ->leftJoin('branches as bankBranch', 'bank_access_branches.branch_id', 'bankBranch.id')
        ->leftJoin('branches as bankParentBranch', 'bankBranch.parent_branch_id', 'bankParentBranch.id')
        ->where('accounts.is_global', BooleanType::False->value);

    $filteredBranchId = 'NULL';

    $branchId = $filteredBranchId;
    // if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
    //     $branchId = auth()->user()->branch_id;

    //     // $query->where('accounts.branch_id', auth()->user()->branch_id);
    // } else {

    //     $branchId = $filteredBranchId;
    // }

    $query->where(function ($query) use ($branchId) {
        $__branchId = $branchId == 'NULL' ? null : $branchId;
        $query->where('accounts.branch_id', 4)
            ->orWhereNull('bank_access_branches.branch_id', 4);
    });

    return $accounts = $query->select(
        'accounts.id',
        'accounts.branch_id',
        'accounts.name',
        'accounts.account_number',
        'accounts.is_global',
        'banks.name as b_name',
        'account_groups.default_balance_type',
        'account_groups.name as group_name',
        'account_groups.sub_sub_group_number',
        'branches.name as branch_name',
        'branches.branch_code',
        'branches.area_name',
        'parentBranch.name as parent_branch_name',
        'bankBranch.id as bank_branch_id',
        'bankBranch.name as bank_branch_name',
        'bankBranch.area_name as bank_branch_area_name',
        'bankParentBranch.name as bank_parent_branch_name',
        DB::raw(
            '
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 0
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS opening_total_debit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 0
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS opening_total_credit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type != 0
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS curr_total_debit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type != 0
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS curr_total_credit
            '
        ),
    )
        // ->orWhere('accounts.is_global', 1)
        ->groupBy(
            'accounts.id',
            'accounts.branch_id',
            'accounts.name',
            'accounts.account_number',
            'accounts.is_global',
            'banks.name',
            'account_groups.default_balance_type',
            'account_groups.name',
            'account_groups.sub_sub_group_number',
            'branches.name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name',
            'bankBranch.id',
            'bankBranch.name',
            'bankBranch.area_name',
            'bankParentBranch.name',
        )->orderBy('account_groups.sorting_number', 'asc')
        ->orderBy('accounts.name', 'asc')->get();
});

Route::get('t-id', function () {
});
