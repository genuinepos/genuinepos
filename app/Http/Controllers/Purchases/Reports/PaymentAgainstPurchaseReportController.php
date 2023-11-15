<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Enums\PurchaseStatus;
use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountingVoucherDescriptionReference;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BranchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentAgainstPurchaseReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('received_against_sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $paidAgainstPurchases = '';
            $query = AccountingVoucherDescriptionReference::query()->with([
                'voucherDescription:id,accounting_voucher_id',
                'voucherDescription.accountingVoucher:id,voucher_no,branch_id,reference,remarks,date,date_ts',
                'voucherDescription.accountingVoucher.branch:id,name,branch_code,area_name,parent_branch_id',
                'voucherDescription.accountingVoucher.branch.parentBranch:id,name',
                'voucherDescription.accountingVoucher.voucherCreditDescription:id,accounting_voucher_id,account_id,payment_method_id,cheque_no,cheque_serial_no',
                'voucherDescription.accountingVoucher.voucherCreditDescription.account:id,name,account_number',
                'voucherDescription.accountingVoucher.voucherCreditDescription.paymentMethod:id,name',
                'purchase:id,invoice_id,supplier_account_id,purchase_status,total_purchase_amount,date,report_date',
                'purchase.supplier:id,name,phone',
            ])->where('purchase_id', '!=', null)
                ->leftJoin('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
                ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');

            $this->filter(request: $request, query: $query);

            $paidAgainstPurchases = $query->select(
                'voucher_description_references.id',
                'voucher_description_references.voucher_description_id',
                'voucher_description_references.purchase_id',
                'voucher_description_references.amount',
                'accounting_voucher_descriptions.id as accounting_voucher_description_id',
                'accounting_voucher_descriptions.accounting_voucher_id as avdid',
                'accounting_voucher_descriptions.account_id as supplier_account_id',
                'accounting_vouchers.id as accounting_voucher_id',
            )->orderBy('accounting_vouchers.date_ts', 'desc');

            return DataTables::of($paidAgainstPurchases)

                ->editColumn('payment_voucher', function ($row) {

                    $paymentVoucherNo = $row->voucherDescription?->accountingVoucher?->voucher_no;
                    $paymentVoucherId = $row->voucherDescription?->accountingVoucher?->id;

                    return '<a href="'.route('payments.show', $paymentVoucherId).'" id="details_btn">'.$paymentVoucherNo.'</a>';
                })

                ->editColumn('payment_date', function ($row) use ($generalSettings) {

                    $date = $row->voucherDescription?->accountingVoucher?->date;

                    return date($generalSettings['business__date_format'], strtotime($date));
                })

                ->editColumn('branch', function ($row) use ($generalSettings) {

                    $branch_id = $row?->voucherDescription?->accountingVoucher?->branch_id;
                    if ($branch_id) {

                        $branch = $row?->voucherDescription?->accountingVoucher?->branch;
                        $parentBranch = $row?->voucherDescription?->accountingVoucher?->branch?->parentBranch;
                        if ($parentBranch) {

                            return $parentBranch->name.'('.$branch->area_name.')';
                        } else {

                            return $branch->name.'('.$branch->area_name.')';
                        }
                    } else {

                        return $generalSettings['business__shop_name'];
                    }
                })

                ->editColumn('purchase_or_order_id', function ($row) {

                    $purchaseId = $row?->purchase?->id;
                    $invoiceId = $row?->purchase?->invoice_id;
                    $purchaseStatus = $row?->purchase?->purchase_status;

                    if ($purchaseStatus == PurchaseStatus::Purchase->value) {

                        return __('Purchase').':<a href="'.route('purchases.show', $purchaseId).'" id="details_btn">'.$invoiceId.'</a>';
                    } elseif ($purchaseStatus == PurchaseStatus::PurchaseOrder->value) {

                        return __('P/o').':<a href="'.route('purchase.orders.show', $purchaseId).'" id="details_btn">'.$invoiceId.'</a>';
                    }
                })

                ->editColumn('purchase_date', function ($row) use ($generalSettings) {

                    $purchaseDate = $row?->purchase?->date;

                    return date($generalSettings['business__date_format'], strtotime($purchaseDate));
                })

                ->editColumn('supplier', function ($row) {

                    return $row?->purchase?->supplier?->name;
                })

                ->editColumn('total_purchase_amount', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row?->sale?->total_purchase_amount);
                })

                ->editColumn('credit_account', function ($row) {

                    $accountName = $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->name;
                    $accountNumber = $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->account?->account_number;

                    $__accountNumber = $accountNumber ? ' / '.$accountNumber : '';

                    return $accountName.$__accountNumber;
                })

                ->editColumn('payment_method', function ($row) {

                    return $row->voucherDescription?->accountingVoucher?->voucherCreditDescription?->paymentMethod?->name;
                })

                ->editColumn('paid_amount', fn ($row) => '<span class="paid_amount text-danger" data-value="'.$row->amount.'">'.\App\Utils\Converter::format_in_bdt($row->amount).'</span>')

                ->rawColumns(['payment_voucher', 'payment_date', 'branch', 'purchase_or_order_id', 'purchase_date', 'supplier', 'total_purchase_amount', 'credit_account', 'payment_method', 'paid_amount'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.payments_against_purchase_report.index', compact('branches', 'supplierAccounts'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredSupplierName = $request->supplier_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $paidAgainstPurchases = '';
        $query = AccountingVoucherDescriptionReference::query()->with([
            'voucherDescription:id,accounting_voucher_id',
            'voucherDescription.accountingVoucher:id,voucher_no,branch_id,reference,remarks,date,date_ts',
            'voucherDescription.accountingVoucher.branch:id,name,branch_code,area_name,parent_branch_id',
            'voucherDescription.accountingVoucher.branch.parentBranch:id,name',
            'voucherDescription.accountingVoucher.voucherCreditDescription:id,accounting_voucher_id,account_id,payment_method_id,cheque_no,cheque_serial_no',
            'voucherDescription.accountingVoucher.voucherCreditDescription.account:id,name,account_number',
            'voucherDescription.accountingVoucher.voucherCreditDescription.paymentMethod:id,name',
            'purchase:id,invoice_id,supplier_account_id,purchase_status,total_purchase_amount,date,report_date',
            'purchase.supplier:id,name,phone',
        ])->where('purchase_id', '!=', null)
            ->leftJoin('accounting_voucher_descriptions', 'voucher_description_references.voucher_description_id', 'accounting_voucher_descriptions.id')
            ->leftJoin('accounting_vouchers', 'accounting_voucher_descriptions.accounting_voucher_id', 'accounting_vouchers.id');

        $this->filter(request: $request, query: $query);

        $paidAgainstPurchases = $query->select(
            'voucher_description_references.id',
            'voucher_description_references.voucher_description_id',
            'voucher_description_references.purchase_id',
            'voucher_description_references.amount',
            'accounting_voucher_descriptions.id as accounting_voucher_description_id',
            'accounting_voucher_descriptions.accounting_voucher_id as avdid',
            'accounting_voucher_descriptions.account_id as supplier_account_id',
            'accounting_vouchers.id as accounting_voucher_id',
        )->orderBy('accounting_vouchers.date_ts', 'desc')->get();

        return view('purchase.reports.payments_against_purchase_report.ajax_view.print', compact('paidAgainstPurchases', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }

    private function filter(object $request, object $query): object
    {
        if (! empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('accounting_vouchers.branch_id', null);
            } else {

                $query->where('accounting_vouchers.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('accounting_voucher_descriptions.account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('accounting_vouchers.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('accounting_vouchers.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
