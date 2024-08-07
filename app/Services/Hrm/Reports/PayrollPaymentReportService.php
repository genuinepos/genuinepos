<?php

namespace App\Services\Hrm\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucherDescription;

class PayrollPaymentReportService
{
    public function PayrollPaymentReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $payments = $this->query(request: $request);

        return DataTables::of($payments)
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row?->accountingVoucher->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('hrm.payroll.payments.show', [$row?->accountingVoucher?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->voucher_no . '</a>';
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row?->accountingVoucher?->branch_id) {

                    if ($row?->accountingVoucher?->branch?->parentBranch) {

                        return $row?->accountingVoucher?->branch?->parentBranch->name . '(' . $row?->accountingVoucher?->branch?->area_name . ')';
                    } else {

                        return $row?->accountingVoucher?->branch?->name . '(' . $row?->accountingVoucher?->branch?->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('reference', function ($row) {

                if ($row?->accountingVoucher?->payrollRef) {

                    return '<a href="' . route('hrm.payrolls.show', [$row?->accountingVoucher?->payrollRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->payrollRef?->voucher_no . '[' . $row?->accountingVoucher?->payrollRef?->month . '-' . $row?->accountingVoucher?->payrollRef?->year . ']' . '</a>';
                }
            })
            ->editColumn('remarks', fn ($row) => '<span title="' . $row?->accountingVoucher?->remarks . '">' . Str::limit($row?->accountingVoucher?->remarks, 25, '') . '</span>')

            ->editColumn('expense_account', fn ($row) => $row?->account?->name)
            ->editColumn('paid_to', fn ($row) => $row?->accountingVoucher?->payrollRef?->user?->prefix . ' ' . $row?->accountingVoucher?->payrollRef?->user?->name . ' ' . $row?->accountingVoucher?->payrollRef?->user?->last_name . ' (' . $row?->accountingVoucher?->payrollRef?->user?->emp_id . ')')
            ->editColumn('paid_from', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->account?->name)
            ->editColumn('payment_method', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->paymentMethod?->name)
            ->editColumn('transaction_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->transaction_no)
            ->editColumn('cheque_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->cheque_no)
            ->editColumn('cheque_serial_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->cheque_serial_no)

            ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="' . $row?->accountingVoucher->total_amount . '">' . \App\Utils\Converter::format_in_bdt($row?->accountingVoucher->total_amount) . '</span>')

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'reference', 'remarks', 'expense_account', 'paid_to', 'paid_from', 'payment_method', 'transaction_no', 'cheque_no', 'cheque_serial_no', 'total_amount'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = AccountingVoucherDescription::query()
            ->with([
                'account:id,name',
                'accountingVoucher:id,branch_id,voucher_no,date,date_ts,voucher_type,payroll_ref_id,total_amount,remarks,created_by_id',
                'accountingVoucher.branch:id,name,branch_code,parent_branch_id,area_name',
                'accountingVoucher.branch.parentBranch:id,name',
                'accountingVoucher.voucherCreditDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
                'accountingVoucher.voucherCreditDescription.account:id,name',
                'accountingVoucher.voucherCreditDescription.paymentMethod:id,name',
                'accountingVoucher.payrollRef:id,voucher_no,user_id,month,year',
                'accountingVoucher.payrollRef.user:id,prefix,name,last_name,emp_id',
            ]);

        $query->where('amount_type', 'dr');

        $query->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');
        $query->leftJoin('hrm_payrolls', 'accounting_vouchers.payroll_ref_id', 'hrm_payrolls.id');
        $query->where('accounting_vouchers.voucher_type', AccountingVoucherType::PayrollPayment->value);

        $this->filter(request: $request, query: $query);

        return $query->select(
            'hrm_payrolls.branch_id',
            'hrm_payrolls.id',
            'hrm_payrolls.user_id',
            'accounting_voucher_descriptions.id as idf',
            'accounting_voucher_descriptions.accounting_voucher_id',
            'accounting_voucher_descriptions.account_id',
        )->orderBy('accounting_vouchers.date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounting_vouchers.branch_id', null);
            } else {

                $query->where('accounting_vouchers.branch_id', $request->branch_id);
            }
        }

        if ($request->user_id) {

            $query->where('hrm_payrolls.user_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('accounting_vouchers.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
