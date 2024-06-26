<?php

namespace App\Services\Accounts\Reports\CashFlow;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class CurrentAssetsCashFlowService
{
    public function currentAsset(object $request): object
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 1)
            // ->where('account_groups.sub_sub_group_number', '!=', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id')
            ->whereNotIn('account_groups.id', function ($query) {
                $query->select('id')
                    ->from('account_groups')
                    ->whereIn('sub_sub_group_number', [6]);
            });

        $filteredBranchId = null;
        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id && !empty($request->child_branch_id) ?
            $request->child_branch_id : null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = $request->branch_id;
            }
        }

        if (isset($filteredBranchId) && !isset($filteredChildBranchId)) {

            $query->where(function ($query) use ($filteredBranchId) {

                $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $query->where('accounts.branch_id', '=', $__branchId);

                if (isset($__branchId)) {

                    $query->orWhere('parentBranch.id', '=', $__branchId);
                }
            });
        } else if (isset($filteredBranchId) && isset($filteredChildBranchId)) {

            $query->where('accounts.branch_id', '=', $filteredChildBranchId);
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('accounts.branch_id', auth()->user()->branch_id);
        }

        if ($fromDateYmd && $toDateYmd) {

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
                                WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                    AND account_ledgers.voucher_type != 0
                                    AND account_ledgers.is_cash_flow = 1
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS cash_out
                    '
                ),

                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                    AND account_ledgers.voucher_type != 0
                                    AND account_ledgers.is_cash_flow = 1
                                    AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS cash_in
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
                                WHEN account_ledgers.voucher_type != 0
                                AND account_ledgers.is_cash_flow = 1
                                THEN account_ledgers.debit
                            END
                        ), 0
                    ) AS cash_out
                    '
                ),
                DB::raw(
                    '
                    IFNULL(
                        SUM(
                            CASE
                                WHEN account_ledgers.voucher_type != 0
                                AND account_ledgers.is_cash_flow = 1
                                THEN account_ledgers.credit
                            END
                        ), 0
                    ) AS cash_in
                    '
                ),
            );
        }

        $results = $query
            ->groupBy(
                'account_groups.id',
                'account_groups.name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id',
                'parentGroup.name',
                'parentGroup.default_balance_type',
                'accounts.id',
                'accountGroup.id',
                'accounts.name',
            )
            // ->groupBy('parentGroup.id')
            // ->groupBy('account_groups.id')
            // // ->groupBy('account_groups.name')
            // ->groupBy('account_groups.sub_group_number')
            // ->groupBy('account_groups.sub_sub_group_number')
            // ->groupBy('accounts.id')
            // // ->groupBy('accounts.name')
            // ->orderBy('account_groups.sub_group_number')
            // ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => 'Current Assets',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $accountReceivable = $this->accountReceivable(request: $request);

        $cashIn = 0;
        $cashOut = 0;
        $cashIn += $accountReceivable->cash_in;
        $cashOut += $accountReceivable->cash_out;

        $prepareMappedArray = $this->prepareMappedArray(results: $results, mappedArray: $mappedArray, externalCashIn: $cashIn, externalCashOut: $cashOut);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        foreach ($accountReceivable->groups as $group) {

            array_push($formattedArray->groups, $group);
        }

        return $formattedArray;
    }

    private function accountReceivable(object $request): object
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

        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_sub_group_number', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($fromDateYmd && $toDateYmd) {

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
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS cash_out
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS cash_in
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
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS cash_out
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS cash_in
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
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS cash_out
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS cash_in
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
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS cash_out
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                            AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS cash_in
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
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS cash_out
                        '
                    ),

                    // DB::raw('IFNULL(SUM(case when ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL' or ($__branchId !== null ? 'parentBranch.id = ' . $branchId : 'parentBranch.id IS NULL')) . ' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1 then account_ledgers.debit end), 0) as cash_out'),

                    // DB::raw('IFNULL(SUM(case when ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL' or ($branchId !== null ? 'parentBranch.id = ' . $__branchId : 'parentBranch.id IS NULL')) . ' and account_ledgers.voucher_type != 0 and account_ledgers.is_cash_flow = 1  then account_ledgers.credit end), 0) as cash_in'),

                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                    ' . ($__branchId !== null ? 'OR parentBranch.id = ' . $__branchId : '') . '
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS cash_in
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
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS cash_out
                        '
                    ),
                    DB::raw(
                        '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($childBranchId !== null ? 'account_ledgers.branch_id = ' . $childBranchId : 'account_ledgers.branch_id IS NULL') . '
                                        AND account_ledgers.voucher_type != 0
                                        AND account_ledgers.is_cash_flow = 1
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS cash_in
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
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS cash_out
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                                            AND account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS cash_in
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
                                        WHEN account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                        THEN account_ledgers.debit
                                    END
                                ), 0
                            ) AS cash_out
                            '
                        ),
                        DB::raw(
                            '
                            IFNULL(
                                SUM(
                                    CASE
                                        WHEN account_ledgers.voucher_type != 0
                                            AND account_ledgers.is_cash_flow = 1
                                        THEN account_ledgers.credit
                                    END
                                ), 0
                            ) AS cash_in
                            '
                        ),
                    );
                }
            }
        }

        $results = $query
            ->groupBy(
                'account_groups.id',
                'account_groups.name',
                'account_groups.sub_group_number',
                'account_groups.sub_sub_group_number',
                'parentGroup.id',
                'parentGroup.name',
                'parentGroup.default_balance_type',
                'accounts.id',
                'accountGroup.id',
                'accounts.name',
            )
            // ->groupBy('parentGroup.id')
            // ->groupBy('account_groups.id')
            // // ->groupBy('account_groups.name')
            // ->groupBy('account_groups.sub_group_number')
            // ->groupBy('account_groups.sub_sub_group_number')
            // ->groupBy('accounts.id')
            // // ->groupBy('accounts.name')
            ->orderBy('account_groups.sub_group_number')
            ->orderBy('account_groups.id')
            ->get();

        $mappedArray = [
            'main_group_id' => '',
            'main_group_name' => 'Account Receivables',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'cash_in' => 0,
            'cash_out' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray(results: $results, mappedArray: $mappedArray);

        return json_decode(json_encode($prepareMappedArray));
    }

    private function prepareMappedArray(object $results, array $mappedArray, float $externalCashIn = 0, float $externalCashOut = 0): array
    {
        foreach ($results as $res) {

            $cashIn = $res->cash_in;
            $cashOut = $res->cash_out;

            if ($res->sub_sub_group_number == null) {

                if ($mappedArray['main_group_id'] == '') {

                    if ($res->account_id) {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['cash_in'] = $cashIn;
                        $mappedArray['cash_out'] = $cashOut;

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'cash_in' => $cashIn,
                            'cash_out' => $cashOut,
                        ]);
                    } else {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['cash_in'] = $cashIn;
                        $mappedArray['cash_out'] = $cashOut;
                    }
                } else {
                    if ($res->account_id && $res->parent_group_id != $mappedArray['main_group_id'] && $res->account_group_id == $mappedArray['main_group_id']) {

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'cash_in' => $cashIn,
                            'cash_out' => $cashOut,
                        ]);
                    } else {

                        if ($res->parent_group_id == $mappedArray['main_group_id']) {

                            $sameSubGroupKey = null;
                            foreach ($mappedArray['groups'] as $key => $value) {

                                if ($value['group_id'] == $res->group_id) {

                                    $sameSubGroupKey = $key;
                                }

                                if (isset($sameSubGroupKey)) {

                                    break;
                                }
                            }

                            if (!isset($sameSubGroupKey)) {

                                array_push($mappedArray['groups'], [
                                    'group_id' => $res->group_id,
                                    'group_name' => $res->group_name,
                                    'parent_group_name' => $res->parent_group_name,
                                    'sub_group_number' => $res->sub_group_number,
                                    'sub_sub_group_number' => $res->sub_sub_group_number,
                                    'cash_in' => $cashIn,
                                    'cash_out' => $cashOut,
                                ]);
                            } else {

                                $mappedArray['groups'][$sameSubGroupKey]['cash_in'] += $cashIn;
                                $mappedArray['groups'][$sameSubGroupKey]['cash_out'] += $cashOut;
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray['groups']) - 1;

                            $mappedArray['groups'][$lastSubGroupKey]['cash_in'] += $cashIn;
                            $mappedArray['groups'][$lastSubGroupKey]['cash_out'] += $cashOut;
                        }
                    }

                    $mappedArray['cash_in'] += $cashIn;
                    $mappedArray['cash_out'] += $cashOut;
                }
            } else {

                $mappedArray['cash_in'] += $cashIn;
                $mappedArray['cash_out'] += $cashOut;

                $subGroupArrIndex = null;
                foreach ($mappedArray['groups'] as $key => $value) {

                    if ($value['sub_group_number'] === $res->sub_group_number && $value['sub_sub_group_number'] === $res->sub_sub_group_number) {

                        $subGroupArrIndex = $key;
                    }

                    if (isset($subGroupArrIndex)) {

                        break;
                    }
                }

                if (isset($subGroupArrIndex)) {

                    $mappedArray['groups'][$subGroupArrIndex]['cash_in'] += $cashIn;
                    $mappedArray['groups'][$subGroupArrIndex]['cash_out'] += $cashOut;
                } else {

                    array_push($mappedArray['groups'], [
                        'group_id' => $res->group_id,
                        'group_name' => $res->group_name,
                        'parent_group_name' => $res->parent_group_name,
                        'sub_group_number' => $res->sub_group_number,
                        'sub_sub_group_number' => $res->sub_sub_group_number,
                        'cash_in' => $cashIn,
                        'cash_out' => $cashOut,
                    ]);
                }
            }
        }

        $mappedArray['cash_in'] += $externalCashIn;
        $mappedArray['cash_out'] += $externalCashOut;

        return $mappedArray;
    }
}
