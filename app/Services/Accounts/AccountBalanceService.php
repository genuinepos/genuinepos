<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;

class AccountBalanceService
{
    public function accountBalance(?int $accountId, string $fromDate = null, string $toDate = null, mixed $branchId = null): array
    {
        $authUserBranchId = auth()?->user()?->branch_id;
        $account = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select(
                'contacts.reward_point',
                'account_groups.default_balance_type',
                'account_groups.sub_sub_group_number',
            )->first();

        if (!isset($account)) {
            return [];
        }

        $converter = new \App\Utils\Converter();
        $amounts = '';
        $query = DB::table('account_ledgers')
        ->where('account_ledgers.account_id', $accountId)
        ->leftJoin('branches', 'account_ledgers.branch_id', 'branches.id')
        ->leftJoin('currencies', 'branches.currency_id', 'currencies.id');

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($fromDate && $toDate) {

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business_or_shop__account_start_date'];

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

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            if ($account->sub_sub_group_number != 6 && $account->sub_sub_group_number != 1) {

                $query->where('account_ledgers.branch_id', auth()->user()->branch_id);
            }
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) < \'' . $fromDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 1
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 1
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_sale
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 2
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 2
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_sales_return
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 3
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 3
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_purchase
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 4
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 4
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_purchase_return
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 8
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 8
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_received
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 9
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                        AND account_ledgers.voucher_type = 9
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_paid
                    '
                ),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type != 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type != 0
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type != 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type != 0
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 1
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_sale
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 2
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 2
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_sales_return
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 3
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 3
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_purchase
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 4
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 4
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_purchase_return
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 8
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 8
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_received
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 9
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 9
                                        AND timestamp(account_ledgers.date) > \'' . $fromDateYmd . '\'
                                        AND timestamp(account_ledgers.date) < \'' . $toDateYmd . '\'
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_paid
                    '
                ),
            );
        } else {

            $query->select(
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 0
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
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
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type != 0
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type != 0
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS curr_total_credit
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 1
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 1
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_sale
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 2
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 2
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_sales_return
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 3
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 3
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_purchase
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 4
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 4
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_purchase_return
                    '
                ),

                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 8
                                    THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 8
                                    THEN account_ledgers.credit
                                END
                            ), 0
                        ) AS total_received
                    '
                ),
                DB::raw(
                    '
                        IFNULL(
                            SUM(
                                CASE
                                    WHEN ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                        AND account_ledgers.voucher_type = 9
                                    THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                                    WHEN account_ledgers.voucher_type = 9
                                    THEN account_ledgers.debit
                                END
                            ), 0
                        ) AS total_paid
                    '
                ),
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
            'reward_point' => $account->reward_point ? $account->reward_point : $converter::format_in_bdt(0),
            'default_balance_type' => $account->default_balance_type,
            'opening_balance_in_flat_amount' => $openingBalanceInFlatAmount ? $openingBalanceInFlatAmount : $converter::format_in_bdt(0),
            'opening_balance_in_flat_amount_string' => $openingBalanceInFlatAmount ? $converter::format_in_bdt($openingBalanceInFlatAmount) : $converter::format_in_bdt(0),
            'total_sale' => $totalSale ? $totalSale : $converter::format_in_bdt(0),
            'total_sale_string' => $totalSale ? $converter::format_in_bdt($totalSale) : $converter::format_in_bdt(0),
            'total_purchase' => $totalPurchase ? $totalPurchase : $converter::format_in_bdt(0),
            'total_purchase_string' => $totalPurchase ? $converter::format_in_bdt($totalPurchase) : $converter::format_in_bdt(0),
            'total_return' => $totalReturn ? $totalReturn : $converter::format_in_bdt(0),
            'total_return_string' => $totalReturn ? $converter::format_in_bdt($totalReturn) : $converter::format_in_bdt(0),
            'total_received' => $totalReceived ? $totalReceived : $converter::format_in_bdt(0),
            'total_received_string' => $totalReceived ? $converter::format_in_bdt($totalReceived) : $converter::format_in_bdt(0),
            'total_paid' => $totalPaid ? $totalPaid : $converter::format_in_bdt(0),
            'total_paid_string' => $totalPaid ? $converter::format_in_bdt($totalPaid) : $converter::format_in_bdt(0),
            'closing_balance_in_flat_amount' => $closingBalanceInFlatAmount ? $closingBalanceInFlatAmount : $converter::format_in_bdt(0),
            'closing_balance_in_flat_amount_string' => $closingBalanceInFlatAmount ? $converter::format_in_bdt($closingBalanceInFlatAmount) : $converter::format_in_bdt(0),
            'opening_balance' => $currOpeningBalance ? $currOpeningBalance : $converter::format_in_bdt(0),
            'opening_balance_string' => $currOpeningBalance ? $converter::format_in_bdt($currOpeningBalance) : $converter::format_in_bdt(0),
            'opening_balance_side' => $currOpeningBalanceSide,
            'curr_total_debit' => $__currTotalDebit ? $__currTotalDebit : $converter::format_in_bdt(0),
            'curr_total_debit_string' => $__currTotalDebit ? $converter::format_in_bdt($__currTotalDebit) : $converter::format_in_bdt(0),
            'curr_total_credit' => $__currTotalCredit ? $__currTotalCredit : $converter::format_in_bdt(0),
            'curr_total_credit_string' => $__currTotalCredit ? $converter::format_in_bdt($__currTotalCredit) : $converter::format_in_bdt(0),
            'all_total_debit' => $allTotalDebit ? $allTotalDebit : $converter::format_in_bdt(0),
            'all_total_debit_string' => $allTotalDebit ? $converter::format_in_bdt($allTotalDebit) : $converter::format_in_bdt(0),
            'all_total_credit' => $allTotalCredit ? $allTotalCredit : $converter::format_in_bdt(0),
            'all_total_credit_string' => $allTotalCredit ? $converter::format_in_bdt($allTotalCredit) : $converter::format_in_bdt(0),
            'closing_balance' => $closingBalance,
            'closing_balance_side' => $closingBalanceSide,
            'closing_balance_string' => $converter::format_in_bdt($closingBalance) . ($closingBalanceSide == 'dr' ? ' Dr.' : ' Cr.'),
        ];
    }
}
