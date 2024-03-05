<?php

namespace App\Services\Accounts;

use App\Enums\AccountingVoucherType;
use App\Enums\BooleanType;
use App\Models\Accounts\AccountingVoucher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ContraService
{
    public function contraTable(object $request)
    {
        $generalSettings = config('generalSettings');
        $contras = '';
        $query = AccountingVoucher::query()
            ->with([
                'branch:id,name,branch_code,parent_branch_id,area_name',
                'branch.parentBranch:id,name',
                'voucherCreditDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
                'voucherCreditDescription.account:id,name,account_number',
                'voucherCreditDescription.paymentMethod:id,name',
                'voucherDebitDescription',
                'voucherDebitDescription.account:id,name,account_number',
                'createdBy:id,prefix,name,last_name',
            ]);

        // if ($request->debit_account_id) {

        //     $query->where('account_id', $request->debit_account_id);
        // }

        $query->where('voucher_type', AccountingVoucherType::Contra->value);

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

        $contras = $query->orderBy('date_ts', 'desc');

        return DataTables::of($contras)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('contras.show', [$row->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('contras_edit')) {

                        $html .= '<a href="' . route('contras.edit', ['id' => $row->id]) . '" class="dropdown-item" id="editContra">' . __('Edit') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('contras_delete')) {

                        $html .= '<a href="' . route('contras.delete', [$row->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
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

                return '<a href="' . route('contras.show', [$row?->id]) . '" id="details_btn">' . $row?->voucher_no . '</a>';
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

            ->editColumn('remarks', fn ($row) => '<span title="' . $row?->remarks . '">' . Str::limit($row?->remarks, 10, '') . '</span>')
            ->editColumn('credit_account', fn ($row) => $row?->voucherCreditDescription?->account?->name . ($row?->voucherCreditDescription?->account?->account_number ? ' / ' . $row?->voucherCreditDescription?->account?->account_number : ''))
            ->editColumn('payment_method', fn ($row) => $row?->voucherCreditDescription?->paymentMethod?->name)
            ->editColumn('transaction_no', fn ($row) => $row?->voucherCreditDescription?->transaction_no)
            ->editColumn('cheque_no', fn ($row) => $row?->voucherCreditDescription?->cheque_no)
            ->editColumn('cheque_serial_no', fn ($row) => $row?->voucherCreditDescription?->cheque_serial_no)
            ->editColumn('debit_account', fn ($row) => $row?->voucherDebitDescription?->account?->name . ($row?->voucherDebitDescription?->account?->account_number ? ' / ' . $row?->voucherDebitDescription?->account?->account_number : ''))
            ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="' . $row?->total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_amount) . '</span>')
            ->editColumn('created_by', function ($row) {

                return $row?->createdBy?->prefix . ' ' . $row?->createdBy?->name . ' ' . $row?->createdBy?->last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'remarks', 'credit_account', 'expense_descriptions', 'payment_method', 'transaction_no', 'cheque_no', 'cheque_serial_no', 'debit_account', 'total_amount', 'created_by'])
            ->make(true);
    }

    public function deleteContra(int $id)
    {
        $deleteContra = $this->singleContra(id: $id);

        if (!is_null($deleteContra)) {

            $deleteContra->delete();
        }

        return $deleteContra;
    }

    public function singleContra(int $id, array $with = null): ?object
    {
        $query = AccountingVoucher::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request): array
    {
        if ($request->received_amount < 1) {

            return ['pass' => false, 'msg' => __('Received amount must be greater then 0')];
        }

        return ['pass' => true];
    }

    public function contraValidation(object $request): ?array
    {
        return $request->validate([
            'date' => 'required|date',
            'received_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);
    }
}
