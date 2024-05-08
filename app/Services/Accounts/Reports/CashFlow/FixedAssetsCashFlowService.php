<?php

namespace App\Services\Accounts\Reports\CashFlow;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class FixedAssetsCashFlowService
{
    public function fixedAssets(object $request): object
    {
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 2)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

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
                $query->where('accounts.branch_id', '=', $__branchId)
                    ->orWhere('parentBranch.id', '=', $__branchId);
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
            'main_group_id' => '',
            'main_group_name' => 'Fixed Assets',
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

    private function prepareMappedArray(object $results, array $mappedArray): array
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

        return $mappedArray;
    }
}
