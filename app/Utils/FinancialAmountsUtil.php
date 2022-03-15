<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialAmountsUtil
{
    public function allFinancialAmounts($request = NULL) : array
    {
        $cashAndBankBalance = $this->cashAndBankBalance($request);

        $cashInHandBalance = $cashAndBankBalance['cash_in_hand_balance'];
        $bankAccountBalance = $cashAndBankBalance['bank_account_balance'];

        $fixedAssetBalance = fixedAssetBalance($request);
    }

    public function cashAndBankBalance($request)
    {
        $cashInHandAmounts = '';
        $cashInHandAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->whereIn('accounts.account_type', [1, 2])
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $cashInHandAmountsQ->where('account_branches.branch_id', NULL);
            } else {

                $cashInHandAmountsQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $cashInHandAmountsQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $cashInHandAmounts = $cashInHandAmountsQ->groupBy('accounts.account_type');
        } else {

            $cashInHandAmounts = $cashInHandAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->groupBy('accounts.account_type');
        }

        $cashInHandDebitCredit = $cashInHandAmounts->select(
            'accounts.account_type',
            DB::raw('SUM(account_ledgers.debit) as total_debit'),
            DB::raw('SUM(account_ledgers.credit) as total_credit')
        )->get();

        $balance = [];

        foreach ($cashInHandAmounts as $cashInHandAmount) {

            if ($cashInHandAmount->account_type == 1) {

                $balance['cash_in_hand_balance'] = $cashInHandAmount->total_debit - $cashInHandAmount->total_credit;
            } else {

                $balance['bank_account_balance'] = $cashInHandAmount->total_debit - $cashInHandAmount->total_credit;
            }
        }

        return $balance;
    }

    public function fixedAssetBalance($request)
    {
        $fixedAssetAmounts = '';
        $fixedAssetAmountsQ = DB::table('account_branches')
            ->leftJoin('accounts', 'account_branches.account_id', 'accounts.id')
            ->where('accounts.account_type', 15)
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id');

        if (isset($request->branch_id) && $request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $fixedAssetAmountsQ->where('account_branches.branch_id', NULL);
            } else {

                $fixedAssetAmountsQ->where('account_branches.branch_id', $request->branch_id);
            }
        }

        if (isset($request->from_date) && $request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $fixedAssetAmountsQ->whereBetween('account_ledgers.date', $date_range);
        }

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {

            $fixedAssetAmounts = $fixedAssetAmountsQ->groupBy('accounts.account_type');
        } else {

            $fixedAssetAmounts = $fixedAssetAmountsQ
                ->where('account_branches.branch_id', auth()->user()->branch_id)
                ->groupBy('accounts.account_type');
        }

        $fixedAssetDebitCredit = $fixedAssetAmounts->select(
            DB::raw('SUM(account_ledgers.debit) as total_debit'),
            DB::raw('SUM(account_ledgers.credit) as total_credit')
        )->get();

        return $fixedAssetDebitCredit->sum('total_debit') - $fixedAssetDebitCredit->sum('total_credit');
    }

}
