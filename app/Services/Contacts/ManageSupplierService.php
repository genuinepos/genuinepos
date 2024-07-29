<?php

namespace App\Services\Contacts;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ManageSupplierService
{
    public function supplierListTable($request)
    {
        $suppliers = '';

        $query = DB::table('contacts')
            ->leftJoin('accounts', 'contacts.id', 'accounts.contact_id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches as ledger_branch', 'account_ledgers.branch_id', 'ledger_branch.id')
            ->leftJoin('currencies', 'ledger_branch.currency_id', 'currencies.id')
            ->where('contacts.type', \App\Enums\ContactType::Supplier->value);

        $dbRaw = $this->dbRaw(request: $request);

        $suppliers = $query->select(
            'contacts.id',
            'contacts.type',
            'contacts.contact_id',
            'contacts.prefix',
            'contacts.name',
            'contacts.business_name',
            'contacts.status',
            'contacts.phone',
            'account_groups.default_balance_type',
            $dbRaw
        )->groupBy(
            'contacts.id',
            'contacts.type',
            'contacts.contact_id',
            'contacts.prefix',
            'contacts.name',
            'contacts.business_name',
            'contacts.status',
            'contacts.phone',
            'account_groups.default_balance_type',
        )->orderBy('contacts.id', 'desc');

        return DataTables::of($suppliers)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';

                if (auth()->user()->can('supplier_manage')) {
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1"><a class="dropdown-item" href="' . route('contacts.manage.supplier.manage', [$row->id]) . '">' . __('Manage') . '</a>';
                }

                if (auth()->user()->can('supplier_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('contacts.edit', [$row->id, \App\Enums\ContactType::Supplier->value]) . '" id="editContact">' . __('Edit') . '</a>';
                }

                if (auth()->user()->can('supplier_delete')) {

                    $html .= '<a class="dropdown-item" id="deleteContact" href="' . route('contacts.delete', [$row->id, $row->type]) . '">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('contact_id', function ($row) {

                return $row->contact_id . '-(' . $row->prefix . ')';
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

            ->editColumn('total_purchase', function ($row) {

                $totalSale = $row->total_purchase;

                return '<span class="total_purchase" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
            })

            ->editColumn('total_sale', function ($row) {

                $totalSale = $row->total_sale;

                return '<span class="total_sale" data-value="' . $totalSale . '">' . \App\Utils\Converter::format_in_bdt($totalSale) . '</span>';
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
            ->rawColumns(['action', 'opening_balance', 'total_purchase', 'total_sale', 'total_return', 'total_received', 'total_paid',  'current_balance', 'status'])
            ->make(true);
    }

    private function dbRaw(object $request): object
    {
        $authUserBranchId = auth()->user()->branch_id;
        $filteredBranchId = null;

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $filteredBranchId = 'NULL';
            } else {

                $filteredBranchId = (int)$request->branch_id;
            }
        }

        if (isset($filteredBranchId)) {

            $__branchId = $filteredBranchId == 'NULL' ? null : $filteredBranchId;

            return DB::raw(
                '
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 0 AND
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS opening_total_debit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 0
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS opening_total_credit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type != 0
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS curr_total_debit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type != 0
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS curr_total_credit,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 1 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                         WHEN account_ledgers.voucher_type = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS total_sale,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 2 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 2
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS total_sales_return,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 3 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 3
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS total_purchase,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 4 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 4
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS total_purchase_return,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 8 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 8
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.credit
                        ELSE 0
                    END
                ) AS total_received,
                SUM(
                    CASE
                        WHEN account_ledgers.voucher_type = 9 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                        WHEN account_ledgers.voucher_type = 9
                            AND ' . ($__branchId !== null ? 'account_ledgers.branch_id = ' . $__branchId : 'account_ledgers.branch_id IS NULL') . '
                        THEN account_ledgers.debit
                        ELSE 0
                    END
                ) AS total_paid
                '
            );
        } else if (!isset($filteredBranchId)) {

            if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

                return DB::raw(
                    '
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 0
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS opening_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 0
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS opening_total_credit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS curr_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type != 0
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS curr_total_credit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 1 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_sale,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 2 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 2
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_sales_return,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 3 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 3
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_purchase,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 4 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 4
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_purchase_return,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 8 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 8
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_received,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 9 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 9
                                AND ' . (auth()->user()->branch_id !== null ? 'account_ledgers.branch_id = ' . auth()->user()->branch_id : 'account_ledgers.branch_id IS NULL') . '
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_paid
                    '
                );
            } else {

                return DB::raw(
                    '
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 0
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS opening_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 0
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS opening_total_credit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type != 0
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS curr_total_debit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type != 0 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type != 0
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS curr_total_credit,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 1 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 1
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_sale,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 2 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 2
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_sales_return,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 3 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 3
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_purchase,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 4 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 4
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_purchase_return,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 8 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.credit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 8
                            THEN account_ledgers.credit
                            ELSE 0
                        END
                    ) AS total_received,
                    SUM(
                        CASE
                            WHEN account_ledgers.voucher_type = 9 AND ' . ($authUserBranchId == null ? 1 : 0) . ' = 1
                            THEN account_ledgers.debit * COALESCE(NULLIF(currencies.currency_rate, 0), 1)
                            WHEN account_ledgers.voucher_type = 9
                            THEN account_ledgers.debit
                            ELSE 0
                        END
                    ) AS total_paid
                    '
                );
            }
        }
    }
}
