<?php

namespace App\Services\Accounts\Reports\TrialBalance;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class GroupWiseTrialBalanceService
{
    public function accountGroups(object $request, string $accountStartDate)
    {
        return $this->queryResult($request, $accountStartDate);
    }

    private function queryResult(object $request, string $accountStartDate)
    {
        $filteredBranchId = null;
        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id && !empty($request->child_branch_id) ?
            $request->child_branch_id : null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        } else {

            if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                $filteredBranchId = auth()->user()->branch_id == null ? 'NULL' : auth()->user()->branch_id;
            }
        }

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.is_main_group', 0)

            // ->where('account_groups.sub_group_number', 1)
            // ->whereNotIn('account_groups.sub_sub_group_number',[6, 8, 10])
            // ->where('account_groups.sub_sub_group_number', '!=', 6)
            // ->where('account_groups.sub_sub_group_number', '!=', 8)
            // ->where('account_groups.sub_sub_group_number', '!=', 10)

            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        if (isset($filteredBranchId) || isset($filteredChildBranchId)) {

            $query->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId, $filteredChildBranchId) {

                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

                $branchId = $filteredChildBranchId ? $filteredChildBranchId : $__filteredBranchId;

                $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                    ->where('bank_access_branches.branch_id', '=', $branchId);
            });
        }

        $query->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id')
            ->whereNotIn('account_groups.id', function ($query) {
                $query->select('id')
                    ->from('account_groups')
                    ->whereIn('sub_sub_group_number', [6, 8, 10]);
            })->whereNotIn('account_groups.id', function ($query) {
                $query->select('id')
                    ->from('account_groups')
                    ->whereIn('sub_group_number', [6]);
            });

        // if ($request->branch_id) {

        //     if ($request->branch_id == 'NULL') {

        //         $query->where('accounts.branch_id', null);
        //     }
        // }

        if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

            $query->where(function ($query) use ($filteredBranchId) {

                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

                $query->where('accounts.branch_id', '=', $__filteredBranchId);

                if (isset($__branchId)) {

                    $query->orWhere('parentBranch.id', '=', $__filteredBranchId);
                }

                $query->orWhere('bank_access_branches.branch_id', '=', $__filteredBranchId);
            });
        } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

            $query->where(function ($query) use ($filteredChildBranchId) {

                $query->where('accounts.branch_id', '=', $filteredChildBranchId)
                    ->orWhere('bank_access_branches.branch_id', '=', $filteredChildBranchId);
            });
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'parentGroup.default_balance_type',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS opening_total_debit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS opening_total_credit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) >  \'' . $fromDateYmd . '\'
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS curr_total_debit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) >  \'' . $fromDateYmd . '\'
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS curr_total_credit
                    '
                ),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'parentGroup.default_balance_type',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        $results = $query
            ->groupBy(
                'parentGroup.id',
                'account_groups.id',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'accounts.id'
            )
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            [
                'main_group_id' => '',
                'main_group_name' => 'Capital A/c',
                'sub_group_number' => '',
                'sub_sub_group_number' => '',
                'opening_total_debit' => 0,
                'opening_total_credit' => 0,
                'curr_total_debit' => 0,
                'curr_total_credit' => 0,
                'groups' => [],
                'accounts' => [],
            ],
        ];

        $queryResult = json_decode($results);

        $lastBankAccountsIndex = -1;
        $lastLiabilitiesIndex = -1;
        $lastCurrentLiabilitiesIndex = -1;
        foreach ($queryResult as $index => $item) {

            if ($item->sub_group_number == 1) {

                $lastBankAccountsIndex = $index;
            }

            if ($item->sub_group_number == 5) {

                $lastLiabilitiesIndex = $index;
            }

            if ($item->sub_group_number == 7) {

                $lastCurrentLiabilitiesIndex = $index;
            }
        }

        $accountReceivableCount = 0;
        if ($lastBankAccountsIndex != -1) {

            $newData = $this->accountReceivableQuery($request, $accountStartDate);
            $accountReceivableCount = count($newData);
            $__newData = json_decode($newData);
            // Insert new data after the last Bank Accounts entry

            array_splice($queryResult, $lastBankAccountsIndex + 1, 0, $__newData);
        }

        $capitalAccountCount = 0;
        if ($lastLiabilitiesIndex != -1) {

            $newData1 = $this->capitalAccountQuery($request, $accountStartDate);
            $capitalAccountCount = count($newData1);
            $__newData1 = json_decode($newData1);
            // Insert new data after the last Bank Accounts entry

            $increase = $accountReceivableCount > 0 ? $accountReceivableCount : 1;

            array_splice($queryResult, $lastLiabilitiesIndex + $increase, 0, $__newData1);
        }

        $dutiesAndTaxCount = 0;
        if ($lastCurrentLiabilitiesIndex != -1) {

            $newData2 = $this->dutiesAndTaxesAccountQuery($request, $accountStartDate);
            $__newData2 = json_decode($newData2);
            $dutiesAndTaxCount = count($__newData2);
            // Insert new data after the last Bank Accounts entry
            $i = $accountReceivableCount + $capitalAccountCount;
            $increase = $i > 0 ? $i : 1;
            array_splice($queryResult, $lastCurrentLiabilitiesIndex + $increase, 0, $__newData2);
        }

        if ($lastCurrentLiabilitiesIndex != -1) {

            $newData3 = $this->accountPayableQuery($request, $accountStartDate);
            $__newData3 = json_decode($newData3);
            // Insert new data after the last Bank Accounts entry
            $i = $accountReceivableCount + $capitalAccountCount + $dutiesAndTaxCount;
            $increase = $i > 0 ? $i : 1;
            array_splice($queryResult, $lastCurrentLiabilitiesIndex + $increase, 0, $__newData3);
        }

        $newJsonData = $queryResult;

        return $prepareMappedArray = $this->prepareMappedArray($newJsonData, $mappedArray);
    }

    private function accountReceivableQuery(object $request, string $accountStartDate)
    {
        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_sub_group_number', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

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

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'parentGroup.default_balance_type',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS opening_total_debit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS opening_total_credit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) >  \'' . $fromDateYmd . '\'
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS curr_total_debit
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) >  \'' . $fromDateYmd . '\'
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS curr_total_credit
                    '
                ),
            );
        } else {

            $query->select(
                'account_groups.id as group_id',
                'account_groups.name as group_name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id as parent_group_id',
                'parentGroup.name as parent_group_name',
                'accounts.id as account_id',
                'accountGroup.id as account_group_id',
                'accounts.name as account_name',

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),
            );
        }

        return $results = $query
            ->groupBy('parentGroup.id')
            ->groupBy('account_groups.id')
            // ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            [
                'main_group_id' => '',
                'main_group_name' => 'Capital A/c',
                'sub_group_number' => '',
                'sub_sub_group_number' => '',
                'opening_total_debit' => 0,
                'opening_total_credit' => 0,
                'curr_total_debit' => 0,
                'curr_total_credit' => 0,
                'groups' => [],
                'accounts' => [],
            ],
        ];

        return $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    private function capitalAccountQuery($request, $accountStartDate)
    {
        $filteredBranchId = null;
        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id && !empty($request->child_branch_id) ?
            $request->child_branch_id : null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        }

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        } else {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        }

        return $results = $query
            ->groupBy('parentGroup.id')
            ->groupBy('account_groups.id')
            // ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();
    }

    private function dutiesAndTaxesAccountQuery($request, $accountStartDate)
    {
        $filteredBranchId = null;
        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id && !empty($request->child_branch_id) ?
            $request->child_branch_id : null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        }

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_sub_group_number', 8)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        } else {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        }

        return $results = $query
            ->groupBy('parentGroup.id')
            ->groupBy('account_groups.id')
            // ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();
    }

    private function accountPayableQuery($request, $accountStartDate)
    {
        $filteredBranchId = null;
        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id && !empty($request->child_branch_id) ?
            $request->child_branch_id : null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        }

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_sub_group_number', 10)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;

                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        } else {

            if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

                $childBranchId = $filteredChildBranchId;
                $query->select(
                    'account_groups.id as group_id',
                    'account_groups.name as group_name',
                    'account_groups.sub_group_number',
                    'account_groups.sub_sub_group_number',
                    'parentGroup.id as parent_group_id',
                    'parentGroup.name as parent_group_name',
                    'parentGroup.default_balance_type',
                    'accounts.id as account_id',
                    'accountGroup.id as account_group_id',
                    'accounts.name as account_name',

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS opening_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS opening_total_credit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS curr_total_debit
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                    AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                        '
                    ),
                );
            } else if (!isset($filteredBranchId) && !isset($filteredChildBranchId)) {

                if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                } else {

                    $query->select(
                        'account_groups.id as group_id',
                        'account_groups.name as group_name',
                        'account_groups.sub_group_number',
                        'account_groups.sub_sub_group_number',
                        'parentGroup.id as parent_group_id',
                        'parentGroup.name as parent_group_name',
                        'parentGroup.default_balance_type',
                        'accounts.id as account_id',
                        'accountGroup.id as account_group_id',
                        'accounts.name as account_name',
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS opening_total_debit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type = 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS opening_total_credit
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS curr_total_debit
                        '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS curr_total_credit
                            '
                        ),
                    );
                }
            }
        }

        return $results = $query
            ->groupBy('parentGroup.id')
            ->groupBy('account_groups.id')
            // ->groupBy('account_groups.name')
            ->groupBy('account_groups.sub_group_number')
            ->groupBy('account_groups.sub_sub_group_number')
            ->groupBy('accounts.id')
            // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();
    }

    private function prepareMappedArray($results, $mappedArray)
    {
        foreach ($results as $res) {

            $acCurrDebitOpBalance = $res->opening_total_debit;
            $acCurrCreditOpBalance = $res->opening_total_credit;

            $acOpeningBalance = 0;
            $acOpeningBalanceSide = 'dr';
            if ($acCurrDebitOpBalance > $acCurrCreditOpBalance) {

                $acOpeningBalance = $acCurrDebitOpBalance - $acCurrCreditOpBalance;
                $acOpeningBalanceSide = 'dr';
            } elseif ($acCurrCreditOpBalance > $acCurrDebitOpBalance) {

                $acOpeningBalance = $acCurrCreditOpBalance - $acCurrDebitOpBalance;
                $acOpeningBalanceSide = 'cr';
            }

            $acCurrDebit = $res->curr_total_debit + ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
            $acCurrCredit = $res->curr_total_credit + ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);

            $closingBalance = 0;
            $closingBalanceSide = 'dr';
            if ($acCurrDebit > $acCurrCredit) {

                $closingBalance = $acCurrDebit - $acCurrCredit;
                $closingBalanceSide = 'dr';
            } elseif ($acCurrCredit > $acCurrDebit) {

                $closingBalance = $acCurrCredit - $acCurrDebit;
                $closingBalanceSide = 'cr';
            }

            if ($res->sub_sub_group_number == null) {

                $mainArrIndex = null;
                foreach ($mappedArray as $key => $arr) {

                    if ($arr['sub_group_number'] == $res->sub_group_number) {

                        $mainArrIndex = $key;
                    }

                    if (isset($mainArrIndex)) {

                        break;
                    }
                }

                if (!isset($mainArrIndex)) {

                    if ($res->account_id) {

                        $mappedArray[] = [
                            'main_group_id' => $res->group_id,
                            'main_group_name' => $res->group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [],
                            'accounts' => [
                                [
                                    'account_id' => $res->account_id,
                                    'account_name' => $res->account_name,
                                    'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                ],
                            ],
                        ];
                    } else {

                        $mappedArray[] = [
                            'main_group_id' => $res->group_id,
                            'main_group_name' => $res->group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [],
                            'accounts' => [],
                        ];
                    }
                } else {

                    if ($res->account_id && $res->parent_group_id != $mappedArray[$mainArrIndex]['main_group_id'] && $res->account_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                        array_push($mappedArray[$mainArrIndex]['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                        ]);
                    } else {

                        if ($res->parent_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                            $sameSubGroupKey = null;
                            foreach ($mappedArray[$mainArrIndex]['groups'] as $key => $value) {

                                if ($value['group_id'] == $res->group_id) {

                                    $sameSubGroupKey = $key;
                                }

                                if (isset($sameSubGroupKey)) {

                                    break;
                                }
                            }

                            if (!isset($sameSubGroupKey)) {

                                array_push($mappedArray[$mainArrIndex]['groups'], [
                                    'group_id' => $res->group_id,
                                    'group_name' => $res->group_name,
                                    'parent_group_name' => $res->parent_group_name,
                                    'sub_group_number' => $res->sub_group_number,
                                    'sub_sub_group_number' => $res->sub_sub_group_number,
                                    'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                ]);
                            } else {

                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray[$mainArrIndex]['groups']) - 1;

                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                        }
                    }

                    $mappedArray[$mainArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                }
            } else {

                $mainArrIndex = null;
                foreach ($mappedArray as $key => $arr) {

                    if ($arr['sub_group_number'] == $res->sub_group_number) {

                        $mainArrIndex = $key;
                    }

                    if (isset($mainArrIndex)) {

                        break;
                    }
                }

                if (!isset($mainArrIndex)) {

                    if ($res->account_id) {

                        $mappedArray[] = [
                            'main_group_id' => $res->parent_group_id,
                            'main_group_name' => $res->parent_group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [
                                [
                                    "group_id" => $res->group_id,
                                    "group_name" =>  $res->group_name,
                                    "parent_group_name" => $res->parent_group_name,
                                    "sub_group_number" => $res->sub_group_number,
                                    "sub_sub_group_number" => $res->sub_sub_group_number,
                                    "debit_opening_balance" => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    "credit_opening_balance" => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    "debit_closing_balance" => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    "credit_closing_balance" => ($closingBalanceSide == 'cr' ? $closingBalance : 0)
                                ]
                            ],
                            'accounts' => [
                                // [
                                //     'account_id' => $res->account_id,
                                //     'account_name' => $res->account_name,
                                //     'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                //     'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                //     'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                //     'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                // ],
                            ],
                        ];
                    } else {

                        $mappedArray[] = [
                            'main_group_id' => $res->group_id,
                            'main_group_name' => $res->group_name,
                            'sub_group_number' => $res->sub_group_number,
                            'sub_sub_group_number' => null,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            'groups' => [],
                            'accounts' => [],
                        ];
                    }
                } else if (isset($mainArrIndex)) {

                    if ($res->account_id && $res->parent_group_id != $mappedArray[$mainArrIndex]['main_group_id'] && $res->account_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                        array_push($mappedArray[$mainArrIndex]['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                            'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                            'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                            'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                        ]);
                    } else {

                        if ($res->parent_group_id == $mappedArray[$mainArrIndex]['main_group_id']) {

                            $sameSubGroupKey = null;
                            foreach ($mappedArray[$mainArrIndex]['groups'] as $key => $value) {

                                if ($value['group_id'] == $res->group_id) {

                                    $sameSubGroupKey = $key;
                                }

                                if (isset($sameSubGroupKey)) {

                                    break;
                                }
                            }

                            if (!isset($sameSubGroupKey)) {

                                array_push($mappedArray[$mainArrIndex]['groups'], [
                                    'group_id' => $res->group_id,
                                    'group_name' => $res->group_name,
                                    'parent_group_name' => $res->parent_group_name,
                                    'sub_group_number' => $res->sub_group_number,
                                    'sub_sub_group_number' => $res->sub_sub_group_number,
                                    'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                    'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                    'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                    'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                                ]);
                            } else {

                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                                $mappedArray[$mainArrIndex]['groups'][$sameSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray[$mainArrIndex]['groups']) - 1;

                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$lastSubGroupKey]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                        }
                    }

                    $mappedArray[$mainArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                    $mappedArray[$mainArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                    $mappedArray[$mainArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                } else if ($closingBalance != 0) {

                    $mainArrIndex = null;
                    foreach ($mappedArray as $key => $arr) {

                        if ($arr['sub_group_number'] == $res->sub_group_number) {

                            $mainArrIndex = $key;
                        }

                        if (isset($mainArrIndex)) {

                            break;
                        }

                        //////////// Commented
                        // foreach ($arr['groups'] as $key => $value) {

                        //     if ($value['sub_group_number'] == $res->sub_group_number && $value['sub_sub_group_number'] == $res->sub_sub_group_number) {

                        //         $subGroupArrIndex = $key;
                        //     }
                        // }

                        // if ($mainArrIndex && $subGroupArrIndex) {

                        //     break;
                        // }
                        //////////// Commented End
                    }

                    if (isset($mainArrIndex)) {

                        $mappedArray[$mainArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                        $mappedArray[$mainArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                        $mappedArray[$mainArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                        $mappedArray[$mainArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);

                        $subGroupArrIndex = null;
                        foreach ($mappedArray[$mainArrIndex]['groups'] as $key => $value) {

                            if (isset($value['sub_group_number']) && $value['sub_group_number'] === $res->sub_group_number && $value['sub_sub_group_number'] === $res->sub_sub_group_number) {

                                $subGroupArrIndex = $key;
                            }

                            if (isset($subGroupArrIndex)) {

                                break;
                            }
                        }

                        if (isset($subGroupArrIndex)) {

                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['debit_opening_balance'] += ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['credit_opening_balance'] += ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['debit_closing_balance'] += ($closingBalanceSide == 'dr' ? $closingBalance : 0);
                            $mappedArray[$mainArrIndex]['groups'][$subGroupArrIndex]['credit_closing_balance'] += ($closingBalanceSide == 'cr' ? $closingBalance : 0);
                        } else {

                            array_push($mappedArray[$mainArrIndex]['groups'], [
                                'group_id' => $res->group_id,
                                'group_name' => $res->group_name,
                                'parent_group_name' => $res->parent_group_name,
                                'sub_group_number' => $res->sub_group_number,
                                'sub_sub_group_number' => $res->sub_sub_group_number,
                                'debit_opening_balance' => ($acOpeningBalanceSide == 'dr' ? $acOpeningBalance : 0),
                                'credit_opening_balance' => ($acOpeningBalanceSide == 'cr' ? $acOpeningBalance : 0),
                                'debit_closing_balance' => ($closingBalanceSide == 'dr' ? $closingBalance : 0),
                                'credit_closing_balance' => ($closingBalanceSide == 'cr' ? $closingBalance : 0),
                            ]);
                        }
                    }
                }
            }
        }

        return $mappedArray;
    }
}
