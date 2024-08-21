<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Accounts\Account;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountCreateAndEditType;
use Yajra\DataTables\Facades\DataTables;

class AccountService
{
    public function accountListTable($request)
    {
        $filteredBranchId = '';
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        } else {

            $filteredBranchId = auth()->user()->branch_id;
        }

        $generalSettings = config('generalSettings');

        $mainQuery = DB::table('accounts')
            ->select(
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
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId) {
                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                    ->where('bank_access_branches.branch_id', '=', $__filteredBranchId);
            })
            ->leftJoin('branches as bankBranch', 'bank_access_branches.branch_id', 'bankBranch.id')
            ->leftJoin('branches as bankParentBranch', 'bankBranch.parent_branch_id', 'bankParentBranch.id')
            ->where('accounts.is_global', BooleanType::False->value);

        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $mainQuery->where('accounts.branch_id', null);
            }
        }

        if ($request->account_group_id) {
            $mainQuery->where('accounts.account_group_id', $request->account_group_id);
        }

        $branchId = null;
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
            $branchId = auth()->user()->branch_id;
        } else {
            $branchId = $filteredBranchId;
        }

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

        $accounts = $mainQuery
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
            )
            ->orderBy('account_groups.sorting_number', 'asc')
            ->orderBy('accounts.name', 'asc');

        return DataTables::of($accounts)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a id="editAccount" class="dropdown-item" href="' . route('accounts.edit', [$row->id, AccountCreateAndEditType::Others->value]) . '" > ' . __('Edit') . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.ledger.index', [$row->id]) . '">' . __('Ledger') . '</a>';
                $html .= '<a class="dropdown-item" href="' . route('accounts.delete', [$row->id]) . '" id="delete">' . __('Delete') . '</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('ac_number', fn ($row) => $row->account_number ? $row->account_number : 'N/A')
            ->editColumn('bank', fn ($row) => $row->b_name ? $row->b_name : 'N/A')
            ->editColumn('group', fn ($row) => '<b>' . $row->group_name . '</b>')
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->is_global == 0) {

                    if ($row->sub_sub_group_number == 1 || $row->sub_sub_group_number == 11) {

                        if ($row->bank_branch_id) {

                            if ($row->bank_parent_branch_name) {

                                return $row->bank_parent_branch_name . '(' . $row->bank_branch_area_name . ')';
                            } else {

                                return $row->bank_branch_name . '(' . $row->bank_branch_area_name . ')';
                            }
                        } else {

                            return $generalSettings['business_or_shop__business_name'];
                        }
                    } else {

                        if ($row->branch_id) {

                            if ($row->parent_branch_name) {

                                return $row->parent_branch_name . '(' . $row->area_name . ')';
                            } else {

                                return $row->branch_name . '(' . $row->area_name . ')';
                            }
                        } else {

                            return $generalSettings['business_or_shop__business_name'];
                        }
                    }

                    // return $row->branch_name ? $row->branch_name . '/' . $row->branch_code : $generalSettings['business_or_shop__business_name'];
                } else {

                    return __('Global Access');
                }
            })
            ->editColumn('opening_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = $row->default_balance_type;

                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                if ($currOpeningBalanceSide == 'dr') {

                    return '<span class="dr_opening_balance" data-value="' . $currOpeningBalance . '">' . \App\Utils\Converter::format_in_bdt($currOpeningBalance) . ' ' . ucfirst($currOpeningBalanceSide) . '.</span>';
                } elseif ($currOpeningBalanceSide == 'cr') {

                    return '<span class="cr_opening_balance" data-value="' . $currOpeningBalance . '">' . \App\Utils\Converter::format_in_bdt($currOpeningBalance) . ' ' . ucfirst($currOpeningBalanceSide) . '.</span>';
                }
            })
            ->editColumn('debit', function ($row) {

                return '<span class="debit" data-value="' . $row->curr_total_debit . '">' . \App\Utils\Converter::format_in_bdt($row->curr_total_debit) . '</span>';
            })
            ->editColumn('credit', function ($row) {

                return '<span class="credit" data-value="' . $row->curr_total_credit . '">' . \App\Utils\Converter::format_in_bdt($row->curr_total_credit) . '</span>';
            })
            ->editColumn('closing_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $CurrTotalDebit = (float) $row->curr_total_debit;
                $CurrTotalCredit = (float) $row->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = 'dr';
                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalance = 0;
                $closingBalanceSide = 'dr';
                if ($CurrTotalDebit > $CurrTotalCredit) {

                    $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                    $closingBalanceSide = 'dr';
                } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                    $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                    $closingBalanceSide = 'cr';
                }

                if ($closingBalanceSide == 'dr') {

                    return '<span class="dr_closing_balance" data-value="' . $closingBalance . '">' . \App\Utils\Converter::format_in_bdt($closingBalance) . ' ' . ucfirst($closingBalanceSide) . '.</span>';
                } elseif ($closingBalanceSide == 'cr') {

                    return '<span class="cr_closing_balance" data-value="' . $closingBalance . '">' . \App\Utils\Converter::format_in_bdt($closingBalance) . ' ' . ucfirst($closingBalanceSide) . '.</span>';
                }
            })
            ->rawColumns(['action', 'ac_number', 'bank', 'group', 'branch', 'opening_balance', 'debit', 'credit', 'closing_balance'])
            ->make(true);
    }

    public function addAccount($name, $accountGroup, $phone = null, $address = null, $accountNumber = null, $bankId = null, $bankAddress = null, $bankCode = null, $swiftCode = null, $bankBranch = null, $taxPercent = null, $openingBalance = 0, $openingBalanceType = 'dr', $remarks = null, $contactId = null)
    {
        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branchId = $accountGroup->sub_sub_group_number == 6 ? $ownBranchIdOrParentBranchId : auth()->user()->branch_id;

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
        $addAccount->opening_balance = $accountGroup->is_global == BooleanType::False->value ? $openingBalance : 0;
        $addAccount->opening_balance_type = $openingBalanceType;
        $addAccount->remark = $remarks;
        $addAccount->created_by_id = auth()->user()->id;
        $addAccount->branch_id = $accountGroup->is_global == 0 ? $branchId : null;
        $addAccount->created_at = Carbon::now();
        $addAccount->is_global = $accountGroup->is_global;
        $addAccount->save();

        return $addAccount;
    }

    public function updateAccount($accountId, $name, $accountGroup, $phone = null, $address = null, $accountNumber = null, $bankId = null, $bankAddress = null, $bankCode = null, $swiftCode = null, $bankBranch = null, $taxPercent = null, $openingBalance = 0, $openingBalanceType = 'dr', $remarks = null, $contactId = null)
    {
        $updateAccount = $this->singleAccountById(id: $accountId, with: ['bankAccessBranches', 'contact', 'accountOpeningBalance']);

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
        $updateAccount->opening_balance = $accountGroup->is_global == BooleanType::False->value ? $openingBalance : 0;
        $updateAccount->opening_balance_type = $openingBalanceType;
        $updateAccount->remark = $remarks;
        $updateAccount->updated_at = Carbon::now();
        $updateAccount->save();

        return $updateAccount;
    }

    public function deleteAccount(int $id): array
    {
        $deleteAccount = $this->singleAccountById(id: $id, with: ['accountLedgersWithOutOpeningBalances', 'contact']);

        if ($deleteAccount->is_fixed == 1) {

            return ['pass' => false, 'msg' => __('Account is not deletable.')];
        }

        if (count($deleteAccount->accountLedgersWithOutOpeningBalances) > 0) {

            return ['pass' => false, 'msg' => __('Account can not be deleted. One or more ledger entries are belonging in this account.')];
        }

        if (!is_null($deleteAccount)) {

            $deleteAccount->delete();
            $deleteAccount?->contact?->delete();
        }

        return ['pass' => true];
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

    public function customerAndSupplierAccounts($ownBranchIdOrParentBranchId, $sortingByGroupNumber = 'asc')
    {
        $customerAccounts = '';
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->where('account_groups.sub_sub_group_number', 6);

        $query->where('accounts.branch_id', $ownBranchIdOrParentBranchId);
        // if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

        //     $query->where('accounts.branch_id', $ownBranchIdOrParentBranchId);
        // }

        $customerAccounts = $query->select(
            'accounts.id',
            'accounts.is_walk_in_customer',
            'accounts.name',
            'accounts.phone',
            'contacts.pay_term_number',
            'contacts.pay_term',
            'account_groups.name as account_group_name',
            'account_groups.sub_sub_group_number',
            'account_groups.default_balance_type',
        );

        return $results = Account::query()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->select(
                'accounts.id',
                'accounts.is_walk_in_customer',
                'accounts.name',
                'accounts.phone',
                'contacts.pay_term_number',
                'contacts.pay_term',
                'account_groups.name as account_group_name',
                'account_groups.sub_sub_group_number',
                'account_groups.default_balance_type',
            )
            ->union($customerAccounts)
            // ->orderBy('IF(accounts.is_walk_in_customer = 1, 0,1)')
            ->orderBy('sub_sub_group_number', $sortingByGroupNumber) // Order by 'is_walk_in_customer' in descending order
            ->orderBy('is_walk_in_customer', 'desc') // Order by 'is_walk_in_customer' in descending order
            ->orderBy('name', 'asc')
            ->get();
    }

    public function customerAccounts(object $request): object
    {
        $customerAccounts = '';
        $query = DB::table('accounts')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6);

        if ($request->customer_account_id) {

            $query->where('accounts.id', $request->customer_account_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounts.branch_id', null);
            } else {

                $query->where('accounts.branch_id', $request->branch_id);
            }
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
            $query->where('accounts.branch_id', $ownBranchIdOrParentBranchId);
        }

        $customerAccounts = $query->select(
            'accounts.id',
            'accounts.is_walk_in_customer',
            'accounts.name',
            'accounts.phone',
            'account_groups.sub_sub_group_number',
            'account_groups.default_balance_type',
            'branches.name as branch_name',
        )->orderBy('is_walk_in_customer', 'desc') // Order by 'is_walk_in_customer' in descending order
            ->orderBy('name', 'asc')
            ->get();

        return $customerAccounts;
    }

    public function supplierAccounts(): object
    {
        return DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.phone',
                'account_groups.sub_sub_group_number',
                'account_groups.default_balance_type',
            )->orderBy('name', 'asc')
            ->get();
    }

    public function branchAccessibleAccounts(?int $ownBranchIdOrParentBranchId, bool $isAllowedWalkInCustomer = true): ?object
    {
        $customerAccounts = '';
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            // ->where('accounts.is_walk_in_customer', 0)
            ->where('account_groups.sub_sub_group_number', 6);

        $query->where('accounts.branch_id', $ownBranchIdOrParentBranchId);
        $customerAccounts = $query->select(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.sub_sub_group_number',
            'account_groups.name as group_name',
        );

        $assets = '';
        $assetsQ = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.main_group_number', 1)
            ->where('accounts.is_walk_in_customer', 0)
            // ->whereNotIn('account_groups.sub_sub_group_number', [1, 2, 6]);
            ->where(function ($query) {
                $query->whereNotIn('account_groups.sub_sub_group_number', [1, 2, 6])
                    ->orWhereNull('account_groups.sub_sub_group_number');
            });

        $assetsQ->where('accounts.branch_id', auth()->user()->branch_id);

        $assets = $assetsQ->select(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.sub_sub_group_number',
            'account_groups.name as group_name',
        );

        $liabilities = '';
        $liabilitiesQ = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.main_group_number', 2)
            ->where('account_groups.is_global', 0)
            ->where('accounts.is_walk_in_customer', 0)
            ->where(function ($query) {
                $query->whereNotIn('account_groups.sub_sub_group_number', [10, 11])
                    ->orWhereNull('account_groups.sub_sub_group_number');
            });
        // ->whereNotIn('account_groups.sub_sub_group_number', [10, 11]);

        $liabilitiesQ->where('accounts.branch_id', auth()->user()->branch_id);

        $liabilities = $liabilitiesQ->select(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.sub_sub_group_number',
            'account_groups.name as group_name',
        );

        $global = '';
        $globalQ = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_global', 1)
            ->where('accounts.is_walk_in_customer', 0)
            ->whereIn('account_groups.sub_group_number', [6, 7])
            // ->whereNotIn('account_groups.sub_sub_group_number', [1, 10, 11]);
            ->where(function ($query) {
                $query->whereNotIn('account_groups.sub_sub_group_number', [1, 10, 11])
                    ->orWhereNull('account_groups.sub_sub_group_number');
            });

        $global = $globalQ->select(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.sub_sub_group_number',
            'account_groups.name as group_name',
        );

        return $results = Account::query()
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 10)
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.phone',
                'account_groups.sub_sub_group_number',
                'account_groups.name as group_name',
            )
            ->union($customerAccounts)
            ->union($assets)
            ->union($liabilities)
            ->union($global)
            // ->orderBy('IF(accounts.is_walk_in_customer = 1, 0,1)')
            ->orderBy('id', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function expenseAccounts(object $request): ?object
    {
        $expenseAccounts = null;
        $query = DB::table('accounts')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->whereIn('account_groups.sub_group_number', [10, 11]);

        if ($request->branch_id && !$request->child_branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounts.branch_id', null);
            } else {

                $query->where('accounts.branch_id', $request->branch_id);
            }
        } else if ($request->branch_id && $request->child_branch_id) {

            $query->where('accounts.branch_id', $request->child_branch_id);
        }

        if ($request->expense_group_id) {

            $query->where('accounts.account_group_id', $request->expense_group_id);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('accounts.branch_id', auth()->user()->branch_id);
        }

        $expenseAccounts = $query->select(
            'accounts.id',
            'accounts.branch_id',
            'accounts.name',
            'account_groups.name as group_name',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
        )->get();

        return $expenseAccounts;
    }

    public function singleAccount(int $id, array $with = null)
    {
        $query = Account::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
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
