<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountBalanceService
{
    public function accountBalance(int $accountId, string $fromDate = null, string $toDate = null, mixed $branchId = null): array
    {
        $account = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select(
                'contacts.reward_point',
                'account_groups.default_balance_type',
                'account_groups.sub_sub_group_number',
            )->first();

        $converter = new \App\Utils\Converter();
        $amounts = '';
        $query = DB::table('account_ledgers')->where('account_ledgers.account_id', $accountId);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($fromDate && $toDate) {

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business__start_date'];

            $fromDateYmd = Carbon::parse($fromDate)->startOfDay();
            $toDateYmd = Carbon::parse($toDate)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($branchId) {

            if ($branchId == 'NULL') {

                $query->where('account_ledgers.branch_id', null);
            } else {

                $query->where('account_ledgers.branch_id', $branchId);
            }
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            if ($account->sub_sub_group_number != 6) {

                $query->where('account_ledgers.branch_id', auth()->user()->branch_id);
            }
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) < '$fromDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 1 then account_ledgers.debit end), 0) as total_sale"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 2 then account_ledgers.credit end), 0) as total_sales_return"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 3 then account_ledgers.credit end), 0) as total_purchase"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 4 then account_ledgers.debit end), 0) as total_purchase_return"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 8 then account_ledgers.credit end), 0) as total_received"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 9 then account_ledgers.debit end), 0) as total_paid"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as opening_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type = 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as opening_total_credit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.debit end), 0) as curr_total_debit"),
                DB::raw("IFNULL(SUM(case when account_ledgers.voucher_type != 0 and timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' then account_ledgers.credit end), 0) as curr_total_credit"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 1 then account_ledgers.debit end), 0) as total_sale"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 2 then account_ledgers.credit end), 0) as total_sales_return"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 3 then account_ledgers.credit end), 0) as total_purchase"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 4 then account_ledgers.debit end), 0) as total_purchase_return"),

                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 8 then account_ledgers.credit end), 0) as total_received"),
                DB::raw("IFNULL(SUM(case when timestamp(account_ledgers.date) > '$fromDateYmd' and timestamp(account_ledgers.date) < '$toDateYmd' and account_ledgers.voucher_type = 9 then account_ledgers.debit end), 0) as total_paid"),
            );
        } else {

            $query->select(
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.debit end), 0) as opening_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 0 then account_ledgers.credit end), 0) as opening_total_credit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.debit end), 0) as curr_total_debit'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type != 0 then account_ledgers.credit end), 0) as curr_total_credit'),

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 1 then account_ledgers.debit end), 0) as total_sale'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 2 then account_ledgers.credit end), 0) as total_sales_return'),

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 3 then account_ledgers.credit end), 0) as total_purchase'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 4 then account_ledgers.debit end), 0) as total_purchase_return'),

                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 8 then account_ledgers.credit end), 0) as total_received'),
                DB::raw('IFNULL(SUM(case when account_ledgers.voucher_type = 9 then account_ledgers.debit end), 0) as total_paid'),
            );
        }

        $amounts = $query->groupBy('account_ledgers.account_id')->get();

        $totalSale = $amounts->sum('total_sale');
        $totalSalesReturn = $amounts->sum('total_sales_return');
        $totalPurchase = $amounts->sum('total_purchase');
        $totalPurchaseReturn = $amounts->sum('total_purchase_return');
        $totalReceived = $amounts->sum('total_received');
        $totalPaid = $amounts->sum('total_paid');

        $openingBalanceDebit = $amounts->sum('opening_total_debit');
        $__openingBalanceDebit = $amounts->sum('opening_total_debit');
        $openingBalanceCredit = $amounts->sum('opening_total_credit');
        $__openingBalanceCredit = $amounts->sum('opening_total_credit');

        $currTotalDebit = $amounts->sum('curr_total_debit');
        $__currTotalDebit = $amounts->sum('curr_total_debit');
        $currTotalCredit = $amounts->sum('curr_total_credit');
        $__currTotalCredit = $amounts->sum('curr_total_credit');

        $currOpeningBalance = 0;
        $currOpeningBalanceSide = $account->default_balance_type;
        if ($openingBalanceDebit > $openingBalanceCredit) {

            $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
            $currOpeningBalanceSide = 'dr';
        } elseif ($openingBalanceCredit > $openingBalanceDebit) {

            $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
            $currOpeningBalanceSide = 'cr';
        }

        $openingBalanceInFlatAmount = 0;
        if ($account->default_balance_type == 'dr') {

            $openingBalanceInFlatAmount = $openingBalanceDebit - $openingBalanceCredit;
        } elseif ($account->default_balance_type == 'cr') {

            $openingBalanceInFlatAmount = $openingBalanceCredit - $openingBalanceDebit;
        }

        $currTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
        $currTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

        $closingBalance = 0;
        $closingBalanceSide = $account->default_balance_type;
        if ($currTotalDebit > $currTotalCredit) {

            $closingBalance = $currTotalDebit - $currTotalCredit;
            $closingBalanceSide = 'dr';
        } elseif ($currTotalCredit > $currTotalDebit) {

            $closingBalance = $currTotalCredit - $currTotalDebit;
            $closingBalanceSide = 'cr';
        }

        $closingBalanceInFlatAmount = 0;
        if ($account->default_balance_type == 'dr') {

            $closingBalanceInFlatAmount = $currTotalDebit - $currTotalCredit;
        } elseif ($account->default_balance_type == 'cr') {

            $closingBalanceInFlatAmount = $currTotalCredit - $currTotalDebit;
        }

        $totalReturn = 0;
        if ($account->default_balance_type == 'dr') {

            $totalReturn = $totalSalesReturn - $totalPurchaseReturn;
        } elseif ($account->default_balance_type == 'cr') {

            $totalReturn = $totalPurchaseReturn - $totalSalesReturn;
        }

        $allTotalDebit = 0;
        $allTotalCredit = 0;
        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $allTotalDebit = $__currTotalDebit + ($currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0);
            $allTotalCredit = $__currTotalCredit + ($currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0);
        } else {

            $allTotalDebit = $__currTotalDebit + $__openingBalanceDebit;
            $allTotalCredit = $__currTotalCredit + $__openingBalanceCredit;
        }

        return [
            'reward_point' => $account->reward_point ? $account->reward_point : 0,
            'opening_balance_in_flat_amount' => $openingBalanceInFlatAmount ? $openingBalanceInFlatAmount : 0.00,
            'opening_balance_in_flat_amount_string' => $openingBalanceInFlatAmount ? $converter::format_in_bdt($openingBalanceInFlatAmount) : 0.00,
            'total_sale' => $totalSale ? $totalSale : 0.00,
            'total_sale_string' => $totalSale ? $converter::format_in_bdt($totalSale) : 0.00,
            'total_purchase' => $totalPurchase ? $totalPurchase : 0.00,
            'total_purchase_string' => $totalPurchase ? $converter::format_in_bdt($totalPurchase) : 0.00,
            'total_return' => $totalReturn ? $totalReturn : 0.00,
            'total_return_string' => $totalReturn ? $converter::format_in_bdt($totalReturn) : 0.00,
            'total_received' => $totalReceived ? $totalReceived : 0.00,
            'total_received_string' => $totalReceived ? $converter::format_in_bdt($totalReceived) : 0.00,
            'total_paid' => $totalPaid ? $totalPaid : 0,
            'total_paid_string' => $totalPaid ? $converter::format_in_bdt($totalPaid) : 0.00,
            'closing_balance_in_flat_amount' => $closingBalanceInFlatAmount ? $closingBalanceInFlatAmount : 0.00,
            'closing_balance_in_flat_amount_string' => $closingBalanceInFlatAmount ? $converter::format_in_bdt($closingBalanceInFlatAmount) : 0.00,
            'opening_balance' => $currOpeningBalance ? $currOpeningBalance : 0.00,
            'opening_balance_string' => $currOpeningBalance ? $converter::format_in_bdt($currOpeningBalance) : 0.00,
            'opening_balance_side' => $currOpeningBalanceSide,
            'curr_total_debit' => $__currTotalDebit ? $__currTotalDebit : 0.00,
            'curr_total_debit_string' => $__currTotalDebit ? $converter::format_in_bdt($__currTotalDebit) : 0.00,
            'curr_total_credit' => $__currTotalCredit ? $__currTotalCredit : 0.00,
            'curr_total_credit_string' => $__currTotalCredit ? $converter::format_in_bdt($__currTotalCredit) : 0.00,
            'all_total_debit' => $allTotalDebit ? $allTotalDebit : 0.00,
            'all_total_debit_string' => $allTotalDebit ? $converter::format_in_bdt($allTotalDebit) : 0.00,
            'all_total_credit' => $allTotalCredit ? $allTotalCredit : 0.00,
            'all_total_credit_string' => $allTotalCredit ? $converter::format_in_bdt($allTotalCredit) : 0.00,
            'closing_balance' => $closingBalance,
            'closing_balance_side' => $closingBalanceSide,
            'closing_balance_string' => $converter::format_in_bdt($closingBalance).($closingBalanceSide == 'dr' ? ' Dr.' : ' Cr.'),
        ];
    }
}
