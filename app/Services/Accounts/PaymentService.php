<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescription;

class PaymentService
{
    public function paymentsTable(object $request, int $debitAccountId = null): object
    {
        $account = DB::table('accounts')->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('accounts.id', $debitAccountId)->select('account_groups.sub_sub_group_number')->first();

        $generalSettings = config('generalSettings');
        $payments = '';
        $query = AccountingVoucherDescription::query()
            ->with([
                'account:id,name,phone,address',
                'accountingVoucher:id,branch_id,voucher_no,date,date_ts,voucher_type,purchase_ref_id,sale_return_ref_id,total_amount,remarks,created_by_id',
                'accountingVoucher.branch:id,name,branch_code,parent_branch_id',
                'accountingVoucher.branch.parentBranch:id,name',
                'accountingVoucher.voucherCreditDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
                'accountingVoucher.voucherCreditDescription.account:id,name',
                'accountingVoucher.voucherCreditDescription.paymentMethod:id,name',
                'accountingVoucher.purchaseRef:id,purchase_status,invoice_id',
                'accountingVoucher.salesReturnRef:id,voucher_no',
                'accountingVoucher.createdBy:id,prefix,name,last_name',
            ]);

        $query->where('amount_type', 'dr');

        if (isset($debitAccountId)) {

            $query->where('account_id', $debitAccountId);
        }

        if ($request->debit_account_id) {

            $query->where('account_id', $request->debit_account_id);
        }

        $query->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');
        $query->where('accounting_vouchers.voucher_type', AccountingVoucherType::Payment->value);

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounting_vouchers.branch_id', null);
            } else {

                $query->where('accounting_vouchers.branch_id', $request->branch_id);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('accounting_vouchers.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
            
            if (!isset($debitAccountId) && $account?->sub_sub_group_number != 6) {

                $query->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
            }
        }

        $payments = $query->select(
            'accounting_voucher_descriptions.id as idf',
            'accounting_voucher_descriptions.accounting_voucher_id',
            'accounting_voucher_descriptions.account_id',
        )->orderBy('accounting_vouchers.date_ts', 'desc');

        return DataTables::of($payments)
            ->addColumn('action', function ($row) use ($debitAccountId) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('payments.show', [$row?->accountingVoucher?->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row?->accountingVoucher?->branch_id) {

                    if (auth()->user()->can('payments_edit')) {

                        $html .= '<a href="' . route('payments.edit', ['id' => $row?->accountingVoucher?->id, 'debitAccountId' => $debitAccountId]) . '" class="dropdown-item" id="editPayment">' . __('Edit') . '</a>';
                    }
                }

                if (auth()->user()->branch_id == $row?->accountingVoucher?->branch_id) {

                    if (auth()->user()->can('payments_delete')) {

                        $html .= '<a href="' . route('payments.delete', [$row?->accountingVoucher?->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                return date($__date_format, strtotime($row?->accountingVoucher->date));
            })
            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('payments.show', [$row?->accountingVoucher?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->voucher_no . '</a>';
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

                if ($row?->accountingVoucher?->purchaseRef) {

                    if ($row?->accountingVoucher?->purchaseRef?->purchase_status == PurchaseStatus::Purchase->value) {
                        return __('Purchase') . ':' . '<a href="' . route('purchases.show', [$row?->accountingVoucher?->purchaseRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->purchaseRef?->invoice_id . '</a>';
                    } else {

                        return __('P/o') . ':' . '<a href="' . route('purchase.orders.show', [$row?->accountingVoucher?->purchaseRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->purchaseRef?->invoice_id . '</a>';
                    }
                } elseif ($row?->accountingVoucher?->salesReturnRef) {

                    return __('Sales Return') . ':' . '<a href="' . route('sales.returns.show', [$row?->accountingVoucher?->salesReturnRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->salesReturnRef?->voucher_no . '</a>';
                }
            })
            ->editColumn('remarks', fn ($row) => '<span title="' . $row?->accountingVoucher?->remarks . '">' . Str::limit($row?->accountingVoucher?->remarks, 25, '') . '</span>')

            ->editColumn('paid_to', fn ($row) => $row?->account?->name)
            ->editColumn('paid_from', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->account?->name)
            ->editColumn('payment_method', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->paymentMethod?->name)
            ->editColumn('transaction_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->transaction_no)
            ->editColumn('cheque_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->cheque_no)
            ->editColumn('cheque_serial_no', fn ($row) => $row?->accountingVoucher?->voucherCreditDescription?->cheque_serial_no)

            ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="' . $row?->accountingVoucher->total_amount . '">' . \App\Utils\Converter::format_in_bdt($row?->accountingVoucher->total_amount) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row?->accountingVoucher?->createdBy?->prefix . ' ' . $row?->accountingVoucher?->createdBy?->name . ' ' . $row?->accountingVoucher?->createdBy?->last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'reference', 'remarks', 'paid_to', 'paid_from', 'payment_method', 'transaction_no', 'cheque_no', 'cheque_serial_no', 'total_amount', 'created_by'])
            ->make(true);
    }

    public function deletePayment(int $id): ?object
    {
        $deletePayment = $this->singlePayment(id: $id, with: [
            'voucherDescriptions',
            'voucherDescriptions.references',
            'voucherDescriptions.references.sale',
            'voucherDescriptions.references.purchase',
            'voucherDescriptions.references.salesReturn',
            'voucherDescriptions.references.purchaseReturn',
            'voucherDescriptions.references.stockAdjustment',
        ]);

        if (!is_null($deletePayment)) {

            $deletePayment->delete();
        }

        return $deletePayment;
    }

    public function singlePayment(int $id, array $with = null): ?object
    {
        $query = AccountingVoucher::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function restrictions(object $request): array
    {
        if ($request->paying_amount < 1) {

            return ['pass' => false, 'msg' => __('Pay amount must be greater then 0')];
        }

        return ['pass' => true];
    }

    public function paymentValidation(object $request): ?array
    {
        return $request->validate([
            'date' => 'required|date',
            'paying_amount' => 'required',
            'payment_method_id' => 'required',
            'debit_account_id' => 'required',
            'credit_account_id' => 'required',
        ]);
    }
}
