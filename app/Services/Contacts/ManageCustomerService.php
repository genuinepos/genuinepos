<?php

namespace App\Services\Contacts;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ManageCustomerService
{
    public function customerListTable($request)
    {
        $customers = '';
        $query = DB::table('contacts')
            ->leftJoin('customer_groups', 'contacts.customer_group_id', 'customer_groups.id')
            ->leftJoin('accounts', 'contacts.id', 'accounts.contact_id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->where('contacts.type', ContactType::Customer->value);

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

        $customers = $query->select(
            'contacts.id',
            'contacts.contact_id',
            'contacts.name',
            'contacts.business_name',
            'contacts.status',
            'contacts.phone',
            'contacts.credit_limit',
            // 'customer_groups.name as group_name',
            'account_groups.default_balance_type',
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
            ),
        )->groupBy(
            'contacts.id',
            'contacts.contact_id',
            'contacts.name',
            'contacts.business_name',
            'contacts.status',
            'contacts.phone',
            'contacts.credit_limit',
            // 'customer_groups.name',
            'account_groups.default_balance_type',
        )->orderBy('contacts.id', 'asc');

        return DataTables::of($customers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';

                if (auth()->user()->can('customer_manage')) {
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . route('contacts.manage.customer.manage', [$row->id]) . '">' . __('Manage') . '</a>';
                }

                if (auth()->user()->can('money_receipt_index')) {

                    $html .= '<a class="dropdown-item" id="money_receipts" href="' . route('contacts.money.receipts.index', [$row->id]) . '">' . __('Money Receipt Vouchers') . '</a>';
                }

                if (auth()->user()->can('customer_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('contacts.edit', [$row->id, ContactType::Customer->value]) . '" id="editContact">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('customer_delete')) {

                    $html .= '<a class="dropdown-item" id="deleteContact" href="' . route('contacts.delete', [$row->id]) . '">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            // ->editColumn('group_name', fn ($row) => $row->group_name ? $row->group_name : '...')

            ->editColumn('credit_limit', function ($row) {

                return $row->credit_limit || $row->credit_limit > 0 ? \App\Utils\Converter::format_in_bdt($row->credit_limit) : 'No Limit';
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

                return '<span class="opening_balance" data-value="' . $openingBalanceInFlatAmount . '">' . \App\Utils\Converter::format_in_bdt($openingBalanceInFlatAmount) . '</span>';
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

                return '<span class="total_return" data-value="' . $totalReturn . '">' . \App\Utils\Converter::format_in_bdt($totalReturn) . '</span>';
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

                return '<span class="current_balance" data-value="' . $closingBalanceInFlatAmount . '">' . \App\Utils\Converter::format_in_bdt($closingBalanceInFlatAmount) . '</span>';
            })

            ->editColumn('status', function ($row) {

                if ($row->status == 1) {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="' . route('contacts.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked/>';
                    $html .= '</div>';

                    return $html;
                } else {

                    $html = '<div class="form-check form-switch">';
                    $html .= '<input class="form-check-input" id="change_status" data-url="' . route('contacts.change.status', [$row->id]) . '" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox" />';
                    $html .= '</div>';

                    return $html;
                }
            })
            ->rawColumns(['action', 'credit_limit', 'business_name', 'opening_balance', 'total_sale', 'total_purchase', 'total_return', 'total_received', 'total_paid',  'current_balance', 'status'])
            ->make(true);
    }
}
