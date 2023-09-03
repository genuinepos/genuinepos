<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AccountService
{
    public function accountListTable($request)
    {
        $generalSettings = config('generalSettings');
        $accounts = '';
        $query = DB::table('accounts')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounts.branch_id', null);
            } else {

                $query->where('accounts.branch_id', $request->branch_id);
            }
        }

        if ($request->account_group_id) {

            $query = $query->where('accounts.account_group_id', $request->account_group_id);
        }

        $query->select(
            'accounts.id',
            'accounts.name',
            'accounts.account_number',
            'accounts.opening_balance',
            'accounts.is_global',
            'banks.name as b_name',
            'account_groups.name as group_name',
            'account_groups.sub_sub_group_number',
            'branches.name as branch_name',
            'branches.branch_code',
        );

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('accounts.branch_id', auth()->user()->branch_id);
        }

        $accounts = $query->orWhere('accounts.is_global', 1)->orderBy('account_groups.sorting_number', 'asc')
            ->orderBy('accounts.name', 'asc')->get();

        return DataTables::of($accounts)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a id="editAccount" class="dropdown-item" href="' . route('accounts.edit', [$row->id]) . '" > ' . __('Edit') . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.ledger', [$row->id]) . '">' . __('Ledger') . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.delete', [$row->id]) . '" id="delete">' . __('Delete') . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('ac_number', fn ($row) => $row->account_number ? $row->account_number : 'Not Applicable')
            ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name : 'Not Applicable')
            ->editColumn('group', fn ($row) => '<b>' . $row->group_name . '</b>')
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->is_global == 0) {

                    return $row->branch_name ? $row->branch_name . '/' . $row->branch_code : $generalSettings['business__shop_name'];
                } else {

                    return __('Global Access');
                }
            })
            ->editColumn('opening_balance', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('debit', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('credit', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('balance', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->rawColumns(['action', 'ac_number', 'bank', 'group', 'branch', 'opening_balance', 'debit', 'credit', 'balance'])
            ->make(true);
    }

    public function addAccount($name, $accountGroup, $phone = null, $address = null, $accountNumber = null, $bankId = null, $bankAddress = null, $bankCode = null, $swiftCode = null, $bankBranch = null, $taxPercent = null, $openingBalance = 0, $openingBalanceType = 'dr', $remarks = null, $contactId = null)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        $addAccount = new Account();
        $addAccount->name = $name;
        $addAccount->phone = $phone;
        $addAccount->address = $address;
        $addAccount->account_number = $accountNumber ? $accountNumber : null;
        $addAccount->bank_id = $bankId ? $bankId : null;
        $addAccount->bank_code = $bankCode ? $bankCode : null;
        $addAccount->swift_code = $swiftCode ? $swiftCode : null;
        $addAccount->bank_branch = $bankBranch ? $bankBranch : null;
        $addAccount->bank_address = $bankAddress ? $bankAddress : null;
        $addAccount->tax_percent = $taxPercent ? $taxPercent : 0;
        $addAccount->contact_id = $contactId;
        $addAccount->account_group_id = $accountGroup->id;
        $addAccount->opening_balance = $openingBalance ? $openingBalance : 0;
        $addAccount->opening_balance_type = $openingBalanceType;
        $addAccount->remark = $remarks;
        $addAccount->created_by_id = auth()->user()->id;
        $addAccount->branch_id = $accountGroup->sub_sub_group_number == 6 ? $ownBranchIdOrParentBranchId : auth()->user()->branch_id;
        $addAccount->created_at = Carbon::now();
        $addAccount->is_global = $accountGroup->is_global;
        $addAccount->save();

        return $addAccount;
    }

    public function updateAccount($accountId, $name, $accountGroup, $phone = null, $address = null, $accountNumber = null, $bankId = null, $bankAddress = null, $bankCode = null, $swiftCode = null, $bankBranch = null, $taxPercent = null, $openingBalance = 0, $openingBalanceType = 'dr', $remarks = null, $contactId = null)
    {
        $updateAccount = Account::with(['bankAccessBranches', 'contact', 'contact.openingBalance'])->where('id', $accountId)->first();
        $updateAccount->name = $name;
        $updateAccount->account_number = $accountNumber ? $accountNumber : null;
        $updateAccount->bank_id = $bankId ? $bankId : null;
        $updateAccount->bank_code = $bankCode ? $bankCode : null;
        $updateAccount->swift_code = $swiftCode ? $swiftCode : null;
        $updateAccount->bank_branch = $bankBranch ? $bankBranch : null;
        $updateAccount->bank_address = $bankAddress ? $bankAddress : null;
        $updateAccount->tax_percent = $taxPercent ? $taxPercent : 0;
        $updateAccount->contact_id = $contactId ?? $updateAccount->contact_id;
        $updateAccount->account_group_id = $accountGroup->id;
        $updateAccount->opening_balance = $openingBalance ? $openingBalance : 0;
        $updateAccount->opening_balance_type = $openingBalanceType;
        $updateAccount->remark = $remarks;
        $updateAccount->updated_at = Carbon::now();
        $updateAccount->save();

        return $updateAccount;
    }

    public function deleteAccount($id)
    {

        $deleteAccount = Account::with('accountLedgersWithOutOpeningBalances', 'contact')->where('id', $id)->first();

        if ($deleteAccount->is_fixed == 1) {

            return ['success' => false, 'msg' => __('Account is not deletable.')];
        }

        if (count($deleteAccount->accountLedgersWithOutOpeningBalances) > 0) {

            return ['success' => false, 'msg' => __('Account can not be deleted. One or more ledger entries are belonging in this account.')];
        }

        if (!is_null($deleteAccount)) {

            $deleteAccount->delete();
            $deleteAccount?->contact?->delete();
        }

        return ['success' => true, 'msg' => __('Account deleted successfully.')];
    }

    public function singleAccountById(int $id, array $with = null)
    {
        $query = Account::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function singleAccountByAnyCondition(array $with = null)
    {
        $query = Account::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function accounts(array $with = null)
    {
        $query = Account::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    function customerAndSupplierAccounts($ownBranchIdOrParentBranchId)
    {
        return $results = Account::query()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->select('accounts.id', 'accounts.name', 'accounts.phone', 'contacts.pay_term_number', 'contacts.pay_term')
            ->union(
                DB::table('accounts')
                    ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                    ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
                    ->where('account_groups.sub_sub_group_number', 6)
                    ->where('accounts.branch_id', $ownBranchIdOrParentBranchId)
                    ->select('accounts.id', 'accounts.name', 'accounts.phone', 'contacts.pay_term_number', 'contacts.pay_term')
            )
            ->orderBy('name', 'asc')
            ->get();
    }

    public function restriction($accountGroup, $accountId)
    {
        $account = Account::with(['group', 'accountLedgersWithOutOpeningBalances'])->where('id', $accountId)->first();

        if (
            $account?->group?->sub_sub_group_number == 6 &&
            $accountGroup->sub_sub_group_number != 6 &&
            count($account->accountLedgersWithOutOpeningBalances) > 0
        ) {

            return ['pass' => false, 'msg' => $account?->group->name . ' ' . __('can be changed to other account group. One or more ledger entries are belonging in this account')];
        }

        if (
            $account?->group?->sub_sub_group_number == 10 &&
            $accountGroup->sub_sub_group_number != 10 &&
            count($account->accountLedgersWithOutOpeningBalances) > 0
        ) {

            return ['pass' => false, 'msg' => $account?->group->name . ' ' . __('can be changed to other account group. One or more ledger entries are belonging in this account')];
        }

        return ['pass' => true];
    }
}
