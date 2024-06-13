<?php

namespace App\Services\Contacts\Reports;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportService
{
    public function customersReportTable(object $request)
    {
        $generalSettings = config('generalSettings');

        $customers = $this->customerReportQuery(request: $request);

        return DataTables::of($customers)

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_name) {

                    return $row->branch_name;
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('opening_balance', function ($row) {

                $openingBalanceDebit = isset($row->opening_total_debit) ? (float) $row->opening_total_debit : 0;
                $openingBalanceCredit = isset($row->opening_total_credit) ? (float) $row->opening_total_credit : 0;

                $openingBalanceInFlatAmount = 0;
                if ($row->default_balance_type == 'dr') {

                    $openingBalanceInFlatAmount = $openingBalanceDebit - $openingBalanceCredit;
                } elseif ($row->default_balance_type == 'cr') {

                    $openingBalanceInFlatAmount = $openingBalanceCredit - $openingBalanceDebit;
                }

                $__openingBalanceInFlatAmount = $openingBalanceInFlatAmount < 0 ? '(<span class="text-danger">' . \App\Utils\Converter::format_in_bdt(abs($openingBalanceInFlatAmount)) . '</span>)' : \App\Utils\Converter::format_in_bdt($openingBalanceInFlatAmount);

                return '<span class="opening_balance" data-value="' . $openingBalanceInFlatAmount . '">' . $__openingBalanceInFlatAmount . '</span>';
            })

            ->editColumn('total_sale', function ($row) {

                $totalSale = $row->total_sale;
                return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
            })

            ->editColumn('total_purchase', function ($row) {

                $totalSale = $row->total_purchase;
                return '<span class="total_purchase" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
            })

            ->editColumn('total_return', function ($row) {

                $totalSalesReturn = $row->total_sales_return;
                $totalPurchaseReturn = $row->total_purchase_return;

                $totalReturn = 0;
                if ($row->default_balance_type == 'dr') {

                    $totalReturn = $totalSalesReturn - $totalPurchaseReturn;
                } elseif ($row->default_balance_type == 'cr') {

                    $totalReturn = $totalPurchaseReturn - $totalSalesReturn;
                }

                $__totalReturn = $totalReturn < 0 ? '(<span class="text-danger">' . \App\Utils\Converter::format_in_bdt(abs($totalReturn)) . '</span>)' : \App\Utils\Converter::format_in_bdt($totalReturn);

                return '<span class="total_return" data-value="' . $totalReturn . '">' . $__totalReturn . '</span>';
            })

            ->editColumn('total_received', function ($row) {

                $totalReceived = $row->total_received;
                return '<span class="total_received" data-value="' . $totalReceived . '">' . \App\Utils\Converter::format_in_bdt($totalReceived) . '</span>';
            })

            ->editColumn('total_paid', function ($row) {

                $totalPaid = $row->total_paid;
                return '<span class="total_paid" data-value="' . $totalPaid . '">' . \App\Utils\Converter::format_in_bdt($totalPaid) . '</span>';
            })

            ->editColumn('current_balance', function ($row) {

                $openingBalanceDebit = $row->opening_total_debit;
                $openingBalanceCredit = $row->opening_total_credit;

                $currTotalDebit = $row->curr_total_debit;
                $currTotalCredit = $row->curr_total_credit;

                $currOpeningBalance = 0;
                $currOpeningBalanceSide = $row->default_balance_type;
                if ($openingBalanceDebit > $openingBalanceCredit) {

                    $currOpeningBalance = $openingBalanceDebit - $openingBalanceCredit;
                    $currOpeningBalanceSide = 'dr';
                } elseif ($openingBalanceCredit > $openingBalanceDebit) {

                    $currOpeningBalance = $openingBalanceCredit - $openingBalanceDebit;
                    $currOpeningBalanceSide = 'cr';
                }

                $currTotalDebit += $currOpeningBalanceSide == 'dr' ? $currOpeningBalance : 0;
                $currTotalCredit += $currOpeningBalanceSide == 'cr' ? $currOpeningBalance : 0;

                $closingBalanceInFlatAmount = 0;
                if ($row->default_balance_type == 'dr') {

                    $closingBalanceInFlatAmount = $currTotalDebit - $currTotalCredit;
                } elseif ($row->default_balance_type == 'cr') {

                    $closingBalanceInFlatAmount = $currTotalCredit - $currTotalDebit;
                }

                $__closingBalanceInFlatAmount = $closingBalanceInFlatAmount < 0 ? '(<span class="text-danger">' . \App\Utils\Converter::format_in_bdt(abs($closingBalanceInFlatAmount)) . '</span>)' : \App\Utils\Converter::format_in_bdt($closingBalanceInFlatAmount);

                return '<span class="current_balance" data-value="' . $closingBalanceInFlatAmount . '">' . $__closingBalanceInFlatAmount . '</span>';
            })

            ->rawColumns(['branch', 'opening_balance', 'total_sale', 'total_purchase', 'total_return', 'total_received', 'total_paid',  'current_balance'])
            ->make(true);
    }

    public function customerReportQuery(object $request): object
    {
        $customers = '';
        $query = DB::table('accounts')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->where('accounts.is_walk_in_customer', BooleanType::False->value)
            ->where('account_groups.sub_sub_group_number', 6);

        $this->filter(request: $request, query: $query);

        $dbRaw = $this->dbRaw();

        return $query->select(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.default_balance_type',
            'branches.name as branch_name',
            $dbRaw
        )->groupBy(
            'accounts.id',
            'accounts.name',
            'accounts.phone',
            'account_groups.default_balance_type',
            'branches.name',
        )->orderBy('accounts.id', 'desc');
    }

    private function dbRaw(): object
    {
        return  DB::raw(
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
                ) AS curr_total_credit,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 1
                    THEN account_ledgers.debit ELSE 0 END
                ) AS total_sale,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 2
                    THEN account_ledgers.credit ELSE 0 END
                ) AS total_sales_return,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 3
                    THEN account_ledgers.credit ELSE 0 END
                ) AS total_purchase,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 4
                    THEN account_ledgers.debit ELSE 0 END
                ) AS total_purchase_return,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 8
                    THEN account_ledgers.credit ELSE 0 END
                ) AS total_received,
                SUM(
                    CASE
                    WHEN account_ledgers.voucher_type = 9
                    THEN account_ledgers.debit ELSE 0 END
                ) AS total_paid
            '
        );
    }

    private function filter(object $request, object $query): object
    {
        if (!empty($request->customer_account_id)) {

            $query->where('accounts.id', $request->customer_account_id);
        }

        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounts.branch_id', null);
            } else {

                $query->where('accounts.branch_id', $request->branch_id);
            }
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
            $query->where('accounts.branch_id', $ownBranchIdOrParentBranchId);
        }

        return $query;
    }
}
