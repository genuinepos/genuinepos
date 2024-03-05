<?php

namespace App\Http\Controllers\Purchases\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;

class PurchaseOrderReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('purchase_order_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $orders = '';
            $query = DB::table('purchases')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
                ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id');

            $this->filter(request: $request, query: $query);

            $orders = $query->select(
                'purchases.id',
                'purchases.branch_id',
                'purchases.date',
                'purchases.invoice_id',
                'purchases.total_item',
                'purchases.total_qty',
                'purchases.is_return_available',
                'purchases.net_total_amount',
                'purchases.order_discount_amount',
                'purchases.purchase_tax_percent',
                'purchases.purchase_tax_amount',
                'purchases.total_purchase_amount',
                'purchases.due',
                'purchases.paid',
                'purchases.po_receiving_status',
                'purchases.po_qty',
                'purchases.po_pending_qty',
                'purchases.po_received_qty',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'suppliers.name as supplier_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
            )->where('purchases.purchase_status', PurchaseStatus::PurchaseOrder->value)->orderBy('purchases.report_date', 'desc');

            return DataTables::of($orders)

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date($generalSettings['business_or_shop__date_format'], strtotime($row->date));
                })

                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->branch_area_name . ')';
                        }
                    } else {

                        return $generalSettings['business_or_shop__business_name'];
                    }
                })

                // ->editColumn('created_by', function ($row) {

                //     return $row->created_prefix . ' ' . $row->created_name . ' ' . $row->created_last_name;
                // })

                ->editColumn('invoice_id', function ($row) {

                    return '<a href="' . route('purchase.orders.show', $row->id) . '" id="details_btn">' . $row->invoice_id . '</a>';
                })

                ->editColumn('po_qty', fn ($row) => '<span class="po_qty" data-value="' . $row->po_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_qty) . '</span>')

                ->editColumn('po_received_qty', fn ($row) => '<span class="po_received_qty text-success" data-value="' . $row->po_received_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_received_qty) . '</span>')

                ->editColumn('po_pending_qty', fn ($row) => '<span class="po_pending_qty text-danger" data-value="' . $row->po_pending_qty . '">' . \App\Utils\Converter::format_in_bdt($row->po_pending_qty) . '</span>')

                ->editColumn('net_total_amount', fn ($row) => '<span class="net_total_amount" data-value="' . $row->net_total_amount . '">' . \App\Utils\Converter::format_in_bdt($row->net_total_amount) . '</span>')

                ->editColumn('order_discount_amount', fn ($row) => '<span class="order_discount_amount" data-value="' . $row->order_discount_amount . '">' . \App\Utils\Converter::format_in_bdt($row->order_discount_amount) . '</span>')

                ->editColumn('purchase_tax_amount', fn ($row) => '<span class="purchase_tax_amount" data-value="' . $row->purchase_tax_amount . '">' . '(' . $row->purchase_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->purchase_tax_amount) . '</span>')

                ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="' . $row->total_purchase_amount . '">' . \App\Utils\Converter::format_in_bdt($row->total_purchase_amount) . '</span>')

                ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . \App\Utils\Converter::format_in_bdt($row->paid) . '</span>')

                ->editColumn('due', fn ($row) => '<span class="text-danger">' . '<span class="due" data-value="' . $row->due . '">' . \App\Utils\Converter::format_in_bdt($row->due) . '</span></span>')

                ->rawColumns(['date', 'branch', 'invoice_id', 'po_qty', 'po_received_qty', 'po_pending_qty', 'net_total_amount', 'order_discount_amount', 'purchase_tax_amount', 'total_purchase_amount', 'paid', 'due'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchase_order_report.index', compact('branches', 'supplierAccounts'));
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

        $orders = '';

        $query = DB::table('purchases')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id');

        $this->filter(request: $request, query: $query);

        $orders = $query->select(
            'purchases.id',
            'purchases.branch_id',
            'purchases.date',
            'purchases.report_date',
            'purchases.invoice_id',
            'purchases.total_item',
            'purchases.total_qty',
            'purchases.is_return_available',
            'purchases.net_total_amount',
            'purchases.order_discount_amount',
            'purchases.purchase_tax_percent',
            'purchases.purchase_tax_amount',
            'purchases.total_purchase_amount',
            'purchases.po_receiving_status',
            'purchases.po_qty',
            'purchases.po_received_qty',
            'purchases.po_pending_qty',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->where('purchases.purchase_status', PurchaseStatus::PurchaseOrder->value)->orderBy('purchases.report_date', 'desc')->get();

        return view('purchase.reports.purchase_order_report.ajax_view.print', compact('orders', 'ownOrParentBranch', 'filteredBranchName', 'filteredSupplierName', 'fromDate', 'toDate'));
    }

    public function filter(object $request, object $query): object
    {
        if (!empty($request->branch_id)) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->payment_status) {

            if ($request->payment_status == PaymentStatus::Paid->value) {

                $query->where('purchases.due', '=', 0);
            } elseif ($request->payment_status == PaymentStatus::Partial->value) {

                $query->where('purchases.paid', '>', 0)->where('purchases.due', '>', 0);
            } elseif ($request->payment_status == PaymentStatus::Due->value) {

                $query->where('purchases.paid', '=', 0);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
