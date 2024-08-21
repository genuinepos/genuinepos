<?php

namespace App\Services\Accounts\Reports\ExpenseReport;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountLedgerVoucherType;
use Yajra\DataTables\Facades\DataTables;

class ExpenseReportService
{
    public function expenseReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $expenses = $this->query(request: $request);

        return DataTables::of($expenses)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')-' . $row->branch_code;
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')-' . $row->branch_code;
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('voucher_type', function ($row) {

                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return $row->voucher_type != 0 ? '<strong>' . $type['name'] . '</strong>' : '';
            })

            ->editColumn('voucher_no', function ($row) {

                $accountLedgerService = new \App\Services\Accounts\AccountLedgerService();
                $type = $accountLedgerService->voucherType($row->voucher_type);

                return '<a href="' . (!empty($type['link']) ? route($type['link'], $row->{$type['details_id']}) : '#') . '" id="details_btn" class="fw-bold">' . $row->{$type['voucher_no']} . '</a>';
            })

            ->editColumn('amount', fn ($row) => '<span class="amount" data-value="' . curr_cnv($row->amount, $row->c_rate, $row->ledger_branch_id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row->amount, $row->c_rate, $row->ledger_branch_id)) . '</span>')
            ->rawColumns(['date', 'branch', 'voucher_type', 'voucher_no', 'amount'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('accounts')
            ->leftJoin('branches', 'accounts.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('account_ledgers', 'accounts.id', 'account_ledgers.account_id')
            ->leftJoin('branches as ledger_branches', 'account_ledgers.branch_id', 'ledger_branches.id')
            ->leftJoin('currencies', 'ledger_branches.currency_id', 'currencies.id')
            ->leftJoin('accounting_voucher_descriptions', 'account_ledgers.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id')
            ->leftJoin('stock_adjustments', 'account_ledgers.adjustment_id', 'stock_adjustments.id')
            ->whereIn('account_groups.sub_group_number', [10, 11])->where('account_ledgers.debit', '>', 0);

        if ($request->expense_group_id) {

            $query->where('accounts.account_group_id', $request->expense_group_id);
        }

        if ($request->expense_account_id) {

            $query->where('accounts.id', $request->expense_account_id);
        }

        $filteredBranchId = null;

        $filteredChildBranchId = isset($request->child_branch_id)
            && $request->child_branch_id &&
            !empty($request->child_branch_id) ?
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

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('account_ledgers.date', $date_range); // Final
        }

        return $query->select(
            'accounts.id as account_id',
            'accounts.branch_id',
            'accounts.name as account_name',
            'account_groups.name as group_name',

            'branches.name as branch_name',
            'branches.area_name as area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',

            'account_ledgers.date',
            'account_ledgers.branch_id as ledger_branch_id',
            'account_ledgers.voucher_type',
            'accounting_vouchers.id as accounting_voucher_id',
            'accounting_vouchers.voucher_no as accounting_voucher_no',
            'stock_adjustments.voucher_no as stock_adjustment_voucher',
            'stock_adjustments.id as adjustment_id',
            'account_ledgers.debit as amount',
            'currencies.currency_rate as c_rate'
        )->orderBy('account_ledgers.date', 'desc');
    }
}
