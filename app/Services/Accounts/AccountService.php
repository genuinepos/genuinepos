<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Models\Account;
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

                $query->where('account_groups.branch_id', NULL);
            } else {

                $query->where('account_groups.branch_id', $request->branch_id);
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
            'banks.name as b_name',
            'account_groups.name as group_name',
            'account_groups.is_global',
            'branches.name as branch_name',
            'branches.branch_code',
        );

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $accounts = $query->orWhere('account_groups.is_global', 1)->orderBy('account_groups.sorting_number', 'asc')
                ->orderBy('accounts.name', 'asc');
        } else {

            $accounts = $query->where('account_groups.branch_id', auth()->user()->branch_id)->orWhere('account_groups.is_global', 1)
                ->orderBy('account_groups.sorting_number', 'asc')
                ->orderBy('accounts.name', 'asc');
        }

        return DataTables::of($accounts)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a id="editAccount" class="dropdown-item" href="' . route('accounts.edit', [$row->id]) . '" > ' . __("Edit") . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.ledger', [$row->id]) . '">' . __('Ledger') . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.delete', [$row->id]) . '" id="delete">' . __("Delete") . '</a>';
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
                }else {

                  return  __('Global A/c');
                }
            })
            ->editColumn('opening_balance', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('debit', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('credit', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->editColumn('balance', fn ($row) => \App\Utils\Converter::format_in_bdt(0.00))
            ->rawColumns(['action', 'ac_number', 'bank', 'group', 'branch', 'opening_balance', 'debit', 'credit', 'balance'])
            ->make(true);
    }

    public function addAccount($name, $accountGroupId, $phone = null, $address = null, $accountNumber = null, $bankId = NULL, $bankAddress = null, $bankCode = null, $swiftCode = null, $bankBranch = null, $taxPercent = null, $openingBalance = 0, $openingBalanceType = 'dr', $remarks = null, $contactId = null)
    {
        $addAccount = new Account();
        $addAccount->name = $name;
        $addAccount->account_number = $accountNumber ? $accountNumber : null;
        $addAccount->bank_id = $bankId ? $bankId : null;
        $addAccount->bank_code = $bankCode ? $bankCode : null;
        $addAccount->swift_code = $swiftCode ? $swiftCode : null;
        $addAccount->bank_branch = $bankBranch ? $bankBranch : null;
        $addAccount->bank_address = $bankAddress ? $bankAddress : null;
        $addAccount->tax_percent = $taxPercent ? $taxPercent : 0;
        $addAccount->contact_id = $contactId;
        $addAccount->account_group_id = $accountGroupId;
        $addAccount->opening_balance = $openingBalance ? $openingBalance : 0;
        $addAccount->opening_balance_type = $openingBalanceType;
        $addAccount->remark = $remarks;
        $addAccount->created_by_id = auth()->user()->id;
        $addAccount->created_at = Carbon::now();
        $addAccount->save();

        return $addAccount;
    }

    public function deleteAccount($id){

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
}
