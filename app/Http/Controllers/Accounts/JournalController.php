<?php

namespace App\Http\Controllers\Accounts;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JournalController extends Controller
{
    public function create()
    {

        return view('accounting.accounting_vouchers.journals.create');
    }

    public function searchAccount(Request $request)
    {
        $replacedStr = str_replace('~', '/', $request->keyword);
        $replacedStr = str_replace('^^^', '#', $replacedStr);
        $__keyword = $request->keyword == 'NULL' ? '' : $replacedStr;
        $accounts = '';

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $filteredBranchId = '';
        if (auth()->user()->branch_id) {

            if (auth()->user()->branch_id == null) {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = auth()->user()->branch_id;
            }
        } else {

            $filteredBranchId = auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL';
        }

        // $userBranchId = auth()->user()->branch_id ? auth()->user()->branch_id : NULL;

        $generalSettings = config('generalSettings');

        $mainQuery = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId) {
                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                    ->where('bank_access_branches.branch_id', '=', $__filteredBranchId);
            })
            ->where('accounts.is_global', BooleanType::False->value)
            ->where('accounts.name', 'LIKE', '%' . $__keyword . '%');

        if (auth()->user()->branch_id == null) {

            $mainQuery->where('accounts.branch_id', null);
        }

        $branchId = $filteredBranchId;

        $mainQuery->where(function ($query) use ($branchId) {
            $__branchId = $branchId == 'NULL' ? null : $branchId;
            $query->where('accounts.branch_id', '=', $__branchId)
                ->orWhere('bank_access_branches.branch_id', '=', $__branchId);
        });

        $mainQuery->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('account_groups')
                ->whereRaw('account_groups.sub_sub_group_number = ?', [6])
                ->whereRaw('account_groups.id = accounts.account_group_id');
        });


        // Global Account
        $global = '';
        $globalQ = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->where('account_groups.is_global', 1)
            ->where('accounts.is_walk_in_customer', 0)
            ->where('accounts.name', 'LIKE', '%' . $__keyword . '%');
        // ->whereIn('account_groups.sub_group_number', [6, 7])
        // ->whereNotIn('account_groups.sub_sub_group_number', [1, 10, 11]);;

        $global = $globalQ->select(
            'accounts.id',
            'accounts.branch_id',
            'accounts.name',
            'accounts.account_number',
            'banks.name as b_name',
            'account_groups.default_balance_type',
            'account_groups.name as group_name',
            'account_groups.sub_sub_group_number',
        );

        $accounts = $mainQuery->union($global)
            ->select(
                'accounts.id',
                'accounts.branch_id',
                'accounts.name',
                'accounts.account_number',
                'banks.name as b_name',
                'account_groups.default_balance_type',
                'account_groups.name as group_name',
                'account_groups.sub_sub_group_number',
            )
            ->orderBy('name', 'asc')->get();


        return $accounts;
    }
}
