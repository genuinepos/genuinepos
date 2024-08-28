<?php

namespace App\Services\Accounts;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Enums\AccountingVoucherType;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Accounts\AccountingVoucher;
use App\Models\Accounts\AccountingVoucherDescription;

class ReceiptService
{
    public function receiptsTable(object $request, int $creditAccountId = null): object
    {
        $account = DB::table('accounts')->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('accounts.id', $creditAccountId)->select('account_groups.sub_sub_group_number')->first();
        $generalSettings = config('generalSettings');
        $receipts = '';
        $query = AccountingVoucherDescription::query()
            ->with([
                'account:id,name,phone,address',
                'accountingVoucher:id,branch_id,voucher_no,date,date_ts,voucher_type,sale_ref_id,purchase_return_ref_id,stock_adjustment_ref_id,total_amount,remarks,created_by_id',
                'accountingVoucher.branch:id,currency_id,name,branch_code,area_name,parent_branch_id',
                'accountingVoucher.branch.parentBranch:id,name',
                'accountingVoucher.branch.branchCurrency:id,currency_rate',
                'accountingVoucher.voucherDebitDescription:id,accounting_voucher_id,account_id,amount_type,amount,payment_method_id,cheque_no,transaction_no,cheque_serial_no',
                'accountingVoucher.voucherDebitDescription.account:id,name',
                'accountingVoucher.voucherDebitDescription.paymentMethod:id,name',
                'accountingVoucher.saleRef:id,status,invoice_id,order_id',
                'accountingVoucher.purchaseReturnRef:id,voucher_no',
                'accountingVoucher.createdBy:id,prefix,name,last_name',
            ]);

        $query->where('amount_type', 'cr');

        if (isset($creditAccountId)) {

            $query->where('account_id', $creditAccountId);
        }

        if ($request->credit_account_id) {

            $query->where('account_id', $request->credit_account_id);
        }

        $query->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');
        $query->where('accounting_vouchers.voucher_type', AccountingVoucherType::Receipt->value);

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

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            if (!isset($creditAccountId) && $account?->sub_sub_group_number != 6) {

                $query->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
            }
        }

        $receipts = $query->select(
            'accounting_voucher_descriptions.id as idf',
            'accounting_voucher_descriptions.accounting_voucher_id',
            'accounting_voucher_descriptions.account_id',
        )->orderBy('accounting_vouchers.date_ts', 'desc');

        return DataTables::of($receipts)
            ->addColumn('action', function ($row) use ($creditAccountId) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('receipts.show', [$row?->accountingVoucher?->id]) . '" class="dropdown-item" id="details_btn">' . __('View') . '</a>';

                if (auth()->user()->branch_id == $row?->accountingVoucher?->branch_id) {

                    if (auth()->user()->can('receipts_edit')) {

                        $html .= '<a href="' . route('receipts.edit', ['id' => $row?->accountingVoucher?->id, 'creditAccountId' => $creditAccountId]) . '" class="dropdown-item" id="editReceipt">' . __('Edit') . '</a>';
                    }

                    if (auth()->user()->can('receipts_delete')) {

                        $html .= '<a href="' . route('receipts.delete', [$row?->accountingVoucher?->id]) . '" class="dropdown-item" id="delete">' . __('Delete') . '</a>';
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

                return '<a href="' . route('receipts.show', [$row?->accountingVoucher?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->voucher_no . '</a>';
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

                if ($row?->accountingVoucher?->saleRef) {

                    if ($row?->accountingVoucher?->saleRef?->status == SaleStatus::Final->value) {

                        return __('Sales') . ':' . '<a href="' . route('sales.show', [$row?->accountingVoucher?->saleRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->saleRef?->invoice_id . '</a>';
                    } else {

                        return __('Sales-Order') . ':' . '<a href="' . route('sale.orders.show', [$row?->accountingVoucher?->saleRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->saleRef?->order_id . '</a>';
                    }
                } elseif ($row?->accountingVoucher?->purchaseReturnRef) {

                    return __('Purchase Return') . ':' . '<a href="' . route('purchase.returns.show', [$row?->accountingVoucher?->purchaseReturnRef?->id]) . '" id="details_btn">' . $row?->accountingVoucher?->purchaseReturnRef?->voucher_no . '</a>';
                }
            })
            ->editColumn('remarks', fn ($row) => '<span title="' . $row?->accountingVoucher?->remarks . '">' . Str::limit($row?->accountingVoucher?->remarks, 25, '') . '</span>')

            ->editColumn('received_from', fn ($row) => $row?->account?->name)
            ->editColumn('received_to', fn ($row) => $row?->accountingVoucher?->voucherDebitDescription?->account?->name)
            ->editColumn('payment_method', fn ($row) => $row?->accountingVoucher?->voucherDebitDescription?->paymentMethod?->name)
            ->editColumn('transaction_no', fn ($row) => $row?->accountingVoucher?->voucherDebitDescription?->transaction_no)
            ->editColumn('cheque_no', fn ($row) => $row?->accountingVoucher?->voucherDebitDescription?->cheque_no)
            ->editColumn('cheque_serial_no', fn ($row) => $row?->accountingVoucher?->voucherDebitDescription?->cheque_serial_no)

            ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="' . curr_cnv($row?->accountingVoucher?->total_amount, $row?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->accountingVoucher?->branch?->id) . '">' . \App\Utils\Converter::format_in_bdt(curr_cnv($row?->accountingVoucher?->total_amount, $row?->accountingVoucher?->branch?->branchCurrency?->currency_rate, $row?->accountingVoucher?->branch?->id)) . '</span>')

            ->editColumn('created_by', function ($row) {

                return $row?->accountingVoucher?->createdBy?->prefix . ' ' . $row?->accountingVoucher?->createdBy?->name . ' ' . $row?->accountingVoucher?->createdBy?->last_name;
            })

            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'reference', 'remarks', 'received_from', 'received_to', 'payment_method', 'transaction_no', 'cheque_no', 'cheque_serial_no', 'total_amount', 'created_by'])
            ->make(true);
    }

    public function deleteReceipt(int $id): ?object
    {
        $deleteReceipt = $this->singleReceipt(id: $id, with: [
            'voucherCreditDescription',
            'voucherCreditDescription.account',
            'voucherDescriptions',
            'voucherDescriptions.account',
            'voucherDescriptions.references',
            'voucherDescriptions.references.sale',
            'voucherDescriptions.references.purchase',
            'voucherDescriptions.references.salesReturn',
            'voucherDescriptions.references.purchaseReturn',
            'voucherDescriptions.references.stockAdjustment',
        ]);

        if (!is_null($deleteReceipt)) {

            $deleteReceipt->delete();
        }

        return $deleteReceipt;
    }

    public function singleReceipt(int $id, array $with = null): ?object
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
}
