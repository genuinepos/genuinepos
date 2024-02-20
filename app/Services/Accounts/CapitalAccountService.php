<?php

namespace App\Services\Accounts;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CapitalAccountService
{
    public function capitalAccountListTable($request)
    {
        // echo $request->all();
        $generalSettings = config('generalSettings');
        $accounts = '';
        $query = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->where('account_groups.main_group_number', 2)
            ->where('account_groups.sub_group_number', 6);

        if ($request->account_group_id) {

            $query = $query->where('accounts.account_group_id', $request->account_group_id);
        }

        $accounts = $query->select(
            'accounts.id',
            'accounts.name',
            'account_groups.default_balance_type',
            'account_groups.name as group_name',
            'account_groups.sub_sub_group_number',
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
            ->groupBy(
                'accounts.id',
                'accounts.name',
                'account_groups.default_balance_type',
                'account_groups.name',
                'account_groups.sub_sub_group_number',
            )
            ->orderBy('account_groups.sorting_number', 'asc')
            ->orderBy('accounts.name', 'asc');

        return DataTables::of($accounts)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('Action').'</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a id="editAccount" class="dropdown-item" href="'.route('accounts.edit', [$row->id]).'" > '.__('Edit').'</a>';
                $html .= '<a class="dropdown-item" href="'.route('accounts.ledger.index', [$row->id]).'">'.__('Ledger').'</a>';
                $html .= '<a class="dropdown-item" href="'.route('accounts.delete', [$row->id]).'" id="delete">'.__('Delete').'</a>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('group', fn ($row) => '<b>'.$row->group_name.'</b>')

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

                    return '<span class="dr_opening_balance" data-value="'.$currOpeningBalance.'">'.\App\Utils\Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide).'.</span>';
                } elseif ($currOpeningBalanceSide == 'cr') {

                    return '<span class="cr_opening_balance" data-value="'.$currOpeningBalance.'">'.\App\Utils\Converter::format_in_bdt($currOpeningBalance).' '.ucfirst($currOpeningBalanceSide).'.</span>';
                }
            })
            ->editColumn('debit', function ($row) {

                return '<span class="debit" data-value="'.$row->curr_total_debit.'">'.\App\Utils\Converter::format_in_bdt($row->curr_total_debit).'</span>';
            })
            ->editColumn('credit', function ($row) {

                return '<span class="credit" data-value="'.$row->curr_total_credit.'">'.\App\Utils\Converter::format_in_bdt($row->curr_total_credit).'</span>';
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

                    return '<span class="dr_closing_balance" data-value="'.$closingBalance.'">'.\App\Utils\Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide).'.</span>';
                } elseif ($closingBalanceSide == 'cr') {

                    return '<span class="cr_closing_balance" data-value="'.$closingBalance.'">'.\App\Utils\Converter::format_in_bdt($closingBalance).' '.ucfirst($closingBalanceSide).'.</span>';
                }
            })
            ->rawColumns(['action', 'group', 'opening_balance', 'debit', 'credit', 'closing_balance'])
            ->make(true);
    }
}
