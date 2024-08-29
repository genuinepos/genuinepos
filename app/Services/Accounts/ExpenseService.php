<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucher as Expense;

class ExpenseService
{
    public function expensesTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $expenses = '';
        $query = Expense::query()
            ->with([
                'branch:id,currency_id,name,area_name,branch_code,parent_branch_id',
                'branch.parentBranch:id,name',
                'branch.branchCurrency:id,currency_rate',
                'voucherCreditDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
                'voucherCreditDescription.account:id,name,account_number',
                'voucherCreditDescription.paymentMethod:id,name',
                'voucherDebitDescriptions',
                'voucherDebitDescriptions.account:id,name',
                'createdBy:id,prefix,name,last_name',
            ]);

        // if ($request->debit_account_id) {

        //     $query->where('account_id', $request->debit_account_id);
        // }

        $query->where('voucher_type', AccountingVoucherType::Expense->value);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('branch_id', null);
            } else {

                $query->where('branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('branch_id', auth()->user()->branch_id);
        }

        $expenses = $query->orderBy('date_ts', 'desc');

        return DataTables::of($expenses)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('expenses.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('expenses_edit')) {

                        $html .= '<a href="' . route('expenses.edit', ['id' => $row->id]) . '" class="dropdown-item" id="editExpense">' . __('Edit') . '</a>';
                    }

                    if (auth()->user()->can('expenses_delete')) {

                        $html .= '<a href="' . route('expenses.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row?->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('expenses.show', [$row?->id]) . '" id="details_btn">' . $row?->voucher_no . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row?->branch_id) {

                    if ($row?->branch?->parentBranch) {

                        return $row?->branch?->parentBranch->name . '(' . $row?->branch?->area_name . ')';
                    } else {

                        return $row?->branch?->name . '(' . $row?->branch?->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })

            ->editColumn('remarks', fn($row) => '<span title="' . $row?->remarks . '">' . Str::limit($row?->remarks, 25, '') . '</span>')

            ->editColumn('paid_from', fn($row) => $row?->voucherCreditDescription?->account?->name . ($row?->voucherCreditDescription?->account?->account_number ? ' / ' . $row?->voucherCreditDescription?->account?->account_number : ''))
            ->editColumn('payment_method', fn($row) => $row?->voucherCreditDescription?->paymentMethod?->name)
            ->editColumn('transaction_no', fn($row) => $row?->voucherCreditDescription?->transaction_no)
            ->editColumn('cheque_no', fn($row) => $row?->voucherCreditDescription?->cheque_no)
            ->editColumn('cheque_serial_no', fn($row) => $row?->voucherCreditDescription?->cheque_serial_no)

            ->editColumn('expense_descriptions', function ($row) {

                $html = '';
                foreach ($row->voucherDebitDescriptions as $index => $description) {

                    $html .= '<p class="p-0 m-0" style="line-height:1.3!important;font-size:11px;">' . ($index + 1) . ' - ' . $description->account->name . ' : <strong>' . \App\Utils\Converter::format_in_bdt(curr_cnv($description->amount, $row?->branch?->branchCurrency?->currency_rate, $row?->branch?->id)) . '</strong></p>';
                }

                return $html;
            })

            ->editColumn('total_amount', fn($row) => '<span class="total_amount" data-value="' . curr_cnv($row?->total_amount, $row?->branch?->branchCurrency?->currency_rate, $row?->branch?->id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row?->total_amount, $row?->branch?->branchCurrency?->currency_rate, $row?->branch?->id)) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row?->createdBy?->prefix . ' ' . $row?->createdBy?->name . ' ' . $row?->createdBy?->last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'remarks', 'paid_from', 'expense_descriptions', 'payment_method', 'transaction_no', 'cheque_no', 'cheque_serial_no', 'total_amount', 'created_by'])
            ->make(true);
    }

    public function deleteExpense(int $id): ?object
    {
        $deleteExpense = $this->singleExpense(id: $id, with: [
            'voucherDebitDescriptions',
            'voucherDebitDescriptions.account',
            'voucherDebitDescription',
            'voucherDebitDescription.account',
            'voucherCreditDescription',
            'voucherCreditDescription.account'
        ]);

        if (!is_null($deleteExpense)) {

            $deleteExpense->delete();
        }

        return $deleteExpense;
    }

    public function singleExpense(int $id, array $with = null): ?object
    {
        $query = Expense::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request): array
    {
        if ($request->total_amount < 1) {

            return ['pass' => false, 'msg' => __('Expense amount must be greater then 0')];
        }

        if (!isset($request->debit_account_ids)) {

            return ['pass' => false, 'msg' => __('Expense ledgers list must not be empty.')];
        }

        return ['pass' => true];
    }

    public function expenseValidation(object $request): ?array
    {
        return $request->validate([
            'date' => 'required|date',
            'total_amount' => 'required',
            'payment_method_id' => 'required',
            'credit_account_id' => 'required',
        ]);
    }
}
