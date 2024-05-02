<?php

namespace App\Services\Accounts\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    public function currentAssetAccountBalance($request, $accountStartDate)
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

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        $query = DB::table('account_groups')
            ->where('account_groups.sub_group_number', 1)
            ->where('account_groups.sub_sub_group_number', '!=', 6)
            ->leftJoin('accounts', 'account_groups.id', 'accounts.account_group_id')
            ->leftJoin('bank_access_branches', function ($join) use ($filteredBranchId) {

                $__filteredBranchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;
                $join->on('accounts.id', '=', 'bank_access_branches.bank_account_id')
                    ->where('bank_access_branches.branch_id', '=', $__filteredBranchId);
            })
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('account_groups as parentGroup', 'account_groups.parent_group_id', 'parentGroup.id')
            ->leftJoin('account_groups as accountGroup', 'accounts.account_group_id', 'accountGroup.id');

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounts.branch_id', null);
            }
        }

        $branchId = null;
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $branchId = auth()->user()->branch_id;
        } else {

            $branchId = $filteredBranchId;
        }

        $query->where(function ($query) use ($branchId) {

            $__branchId = $branchId == 'NULL' ? null : $branchId;
            $query->where('accounts.branch_id', '=', $__branchId)
                ->orWhere('bank_access_branches.branch_id', '=', $__branchId);
        });

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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
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
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

         $accountReceivableAmount = $this->accountReceivableAmount($request, $accountStartDate);

        $formattedArray = json_decode(json_encode($prepareMappedArray));

        $this->mainGroupClosingBalance($formattedArray, 'dr', $accountReceivableAmount['accountReceivable']->closing_balance, $accountReceivableAmount['accountReceivable']->closing_balance_side);

        $this->subGroupClosingBalance($formattedArray, 'dr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'dr');

        foreach($accountReceivableAmount['accountReceivable']->groups as $group){

            array_push($formattedArray->groups, $group);
        }

        return ['currentAssetsAccounts' => $formattedArray];
    }

    private function accountReceivableAmount($request, $accountStartDate)
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
            }else {

                $query->where('accounts.branch_id', $request->branch_id);
            }
        }

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('accounts.branch_id', auth()->user()->branch_id);
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

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),
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

                DB::raw('IFNULL(SUM(account_ledgers.debit), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(account_ledgers.credit), 0) as curr_total_credit'),
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
            'main_group_name' => 'Capital A/c',
            'sub_group_number' => '',
            'sub_sub_group_number' => '',
            'opening_total_debit' => 0,
            'opening_total_credit' => 0,
            'curr_total_debit' => 0,
            'curr_total_credit' => 0,
            'closing_balance' => 0,
            'closing_balance_side' => 0,
            'groups' => [],
            'accounts' => [],
        ];

        $prepareMappedArray = $this->prepareMappedArray($results, $mappedArray);

        $formattedArray = json_decode(json_encode($prepareMappedArray));
        $this->mainGroupClosingBalance($formattedArray, 'dr', 0, 'dr');
        $this->subGroupClosingBalance($formattedArray, 'dr');
        $this->mainGroupAccountClosingBalance($formattedArray, 'dr');

        return ['accountReceivable' => $formattedArray];
    }

    public function prepareMappedArray($results, $mappedArray)
    {
        foreach ($results as $res) {

            $acCurrDebitOpBalance = isset($res->opening_total_debit) ? $res->opening_total_debit : 0;
            $acCurrCreditOpBalance = isset($res->opening_total_credit) ? $res->opening_total_credit : 0;

            $acCurrDebit = $res->curr_total_debit;
            $acCurrCredit = $res->curr_total_credit;

            if ($res->sub_sub_group_number == null) {

                if ($mappedArray['main_group_id'] == '') {

                    if ($res->account_id) {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['opening_total_debit'] = $acCurrDebitOpBalance;
                        $mappedArray['opening_total_credit'] = $acCurrCreditOpBalance;
                        $mappedArray['curr_total_debit'] = $acCurrDebit;
                        $mappedArray['curr_total_credit'] = $acCurrCredit;

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'opening_total_debit' => $acCurrDebitOpBalance,
                            'opening_total_credit' => $acCurrCreditOpBalance,
                            'curr_total_debit' => $acCurrDebit,
                            'curr_total_credit' => $acCurrCredit,
                        ]);
                    } else {

                        $mappedArray['main_group_id'] = $res->group_id;
                        $mappedArray['main_group_name'] = $res->group_name;
                        $mappedArray['sub_group_number'] = $res->sub_group_number;
                        $mappedArray['sub_sub_group_number'] = null;
                        $mappedArray['opening_total_debit'] = $acCurrDebitOpBalance;
                        $mappedArray['opening_total_credit'] = $acCurrCreditOpBalance;
                        $mappedArray['curr_total_debit'] = $acCurrDebit;
                        $mappedArray['curr_total_credit'] = $acCurrCredit;
                    }
                } else {

                    if ($res->account_id && $res->parent_group_id != $mappedArray['main_group_id'] && $res->account_group_id == $mappedArray['main_group_id']) {

                        array_push($mappedArray['accounts'], [
                            'account_id' => $res->account_id,
                            'account_name' => $res->account_name,
                            'opening_total_debit' => $acCurrDebitOpBalance,
                            'opening_total_credit' => $acCurrCreditOpBalance,
                            'curr_total_debit' => $acCurrDebit,
                            'curr_total_credit' => $acCurrCredit,
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
                                    'opening_total_debit' => $acCurrDebitOpBalance,
                                    'opening_total_credit' => $acCurrCreditOpBalance,
                                    'curr_total_debit' => $acCurrDebit,
                                    'curr_total_credit' => $acCurrCredit,
                                ]);
                            } else {

                                $mappedArray['groups'][$sameSubGroupKey]['opening_total_debit'] += $acCurrDebitOpBalance;
                                $mappedArray['groups'][$sameSubGroupKey]['opening_total_credit'] += $acCurrCreditOpBalance;
                                $mappedArray['groups'][$sameSubGroupKey]['curr_total_debit'] += $acCurrDebit;
                                $mappedArray['groups'][$sameSubGroupKey]['curr_total_credit'] += $acCurrCredit;
                            }
                        } else {

                            $lastSubGroupKey = count($mappedArray['groups']) - 1;

                            $mappedArray['groups'][$lastSubGroupKey]['opening_total_debit'] += $acCurrDebitOpBalance;
                            $mappedArray['groups'][$lastSubGroupKey]['opening_total_credit'] += $acCurrCreditOpBalance;
                            $mappedArray['groups'][$lastSubGroupKey]['curr_total_debit'] += $acCurrDebit;
                            $mappedArray['groups'][$lastSubGroupKey]['curr_total_credit'] += $acCurrCredit;
                        }
                    }

                    $mappedArray['opening_total_debit'] += $acCurrDebitOpBalance;
                    $mappedArray['opening_total_credit'] += $acCurrCreditOpBalance;
                    $mappedArray['curr_total_debit'] += $acCurrDebit;
                    $mappedArray['curr_total_credit'] += $acCurrCredit;
                }
            } else {

                // if ($acCurrDebitOpBalance != 0 || $acCurrCreditOpBalance != 0 || $acCurrDebit != 0 || $acCurrCredit != 0) {

                $mappedArray['opening_total_debit'] += $acCurrDebitOpBalance;
                $mappedArray['opening_total_credit'] += $acCurrCreditOpBalance;
                $mappedArray['curr_total_debit'] += $acCurrDebit;
                $mappedArray['curr_total_credit'] += $acCurrCredit;

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

                    $mappedArray['groups'][$subGroupArrIndex]['opening_total_debit'] += $acCurrDebitOpBalance;
                    $mappedArray['groups'][$subGroupArrIndex]['opening_total_credit'] += $acCurrCreditOpBalance;
                    $mappedArray['groups'][$subGroupArrIndex]['curr_total_debit'] += $acCurrDebit;
                    $mappedArray['groups'][$subGroupArrIndex]['curr_total_credit'] += $acCurrCredit;
                } else {

                    array_push($mappedArray['groups'], [
                        'group_id' => $res->group_id,
                        'group_name' => $res->group_name,
                        'parent_group_name' => $res->parent_group_name,
                        'sub_group_number' => $res->sub_group_number,
                        'sub_sub_group_number' => $res->sub_sub_group_number,
                        'opening_total_debit' => $acCurrDebitOpBalance,
                        'opening_total_credit' => $acCurrCreditOpBalance,
                        'curr_total_debit' => $acCurrDebit,
                        'curr_total_credit' => $acCurrCredit,
                    ]);
                }
                // }
            }
        }

        return $mappedArray;
    }

    public function mainGroupClosingBalance($arr, $defaultBalanceSide, $externalClosingBalance, $externalClosingBalanceSide)
    {
        $openingBalanceDebit = isset($arr->opening_total_debit) ? (float) $arr->opening_total_debit : 0;
        $openingBalanceCredit = isset($arr->opening_total_credit) ? (float) $arr->opening_total_credit : 0;

        $CurrTotalDebit = (float) $arr->curr_total_debit;
        $CurrTotalCredit = (float) $arr->curr_total_credit;

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = $defaultBalanceSide;

        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $CurrTotalDebit += $externalClosingBalanceSide == 'dr' ? $externalClosingBalance : 0;
        $CurrTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
        $CurrTotalCredit += $externalClosingBalanceSide == 'cr' ? $externalClosingBalance : 0;
        $CurrTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

        $closingBalance = 0;
        $closingBalanceSide = $defaultBalanceSide;
        if ($CurrTotalDebit > $CurrTotalCredit) {

            $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
            $closingBalanceSide = 'dr';
        } elseif ($CurrTotalCredit > $CurrTotalDebit) {

            $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
            $closingBalanceSide = 'cr';
        }

        $arr->closing_balance = $closingBalance;
        $arr->closing_balance_side = $closingBalanceSide;
    }

    public function subGroupClosingBalance($arr, $defaultBalanceSide)
    {
        foreach ($arr->groups as $group) {

            $openingBalanceDebit = isset($group->opening_total_debit) ? (float) $group->opening_total_debit : 0;
            $openingBalanceCredit = isset($group->opening_total_credit) ? (float) $group->opening_total_credit : 0;

            $CurrTotalDebit = (float) $group->curr_total_debit;
            $CurrTotalCredit = (float) $group->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = $defaultBalanceSide;

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
            $closingBalanceSide = $defaultBalanceSide;
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                $closingBalanceSide = 'dr';
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $group->closing_balance = $closingBalance;
            $group->closing_balance_side = $closingBalanceSide;
        }
    }

    public function mainGroupAccountClosingBalance($arr, $defaultBalanceSide)
    {
        foreach ($arr->accounts as $account) {

            $openingBalanceDebit = isset($account->opening_total_debit) ? (float) $account->opening_total_debit : 0;
            $openingBalanceCredit = isset($account->opening_total_credit) ? (float) $account->opening_total_credit : 0;

            $CurrTotalDebit = (float) $account->curr_total_debit;
            $CurrTotalCredit = (float) $account->curr_total_credit;

            $currOpeningBalance = 0;
            $currOpeningBalanceSide = $defaultBalanceSide;
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
            $closingBalanceSide = $defaultBalanceSide;
            if ($CurrTotalDebit > $CurrTotalCredit) {

                $closingBalance = $CurrTotalDebit - $CurrTotalCredit;
                $closingBalanceSide = 'dr';
            } elseif ($CurrTotalCredit > $CurrTotalDebit) {

                $closingBalance = $CurrTotalCredit - $CurrTotalDebit;
                $closingBalanceSide = 'cr';
            }

            $account->closing_balance = $closingBalance;
            $account->closing_balance_side = $closingBalanceSide;
        }
    }
}
