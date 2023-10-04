<?php

namespace App\Http\Controllers\Purchases\Reports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\PurchaseStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Setups\WarehouseService;
use App\Services\Accounts\AccountFilterService;

class PurchaseProductReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private WarehouseService $warehouseService,
        private BranchService $branchService,
    ) {
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $generalSettings = config('generalSettings');
            $purchaseProducts = '';
            $query = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('products', 'purchase_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_products.variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'purchase_products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
                ->where('purchases.purchase_status', PurchaseStatus::Purchase->value);

            if ($request->product_id) {

                $query->where('purchase_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_products.variant_id', $request->variant_id);
            }

            if ($request->branch_id) {

                if ($request->branch_id == 'NULL') {

                    $query->where('purchases.branch_id', null);
                } else {

                    $query->where('purchases.branch_id', $request->branch_id);
                }
            }

            if ($request->supplier_account_id) {

                $query->where('purchases.supplier_account_id', $request->supplier_account_id);
            }

            if ($request->category_id) {

                $query->where('products.category_id', $request->category_id);
            }

            if ($request->sub_category_id) {

                $query->where('products.sub_category_id', $request->sub_category_id);
            }

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('purchases.report_date', $date_range); // Final
            }

            if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

                $query->where('purchases.branch_id', auth()->user()->branch_id);
            }

            $purchaseProducts = $query->select(
                'purchase_products.purchase_id',
                'purchase_products.product_id',
                'purchase_products.variant_id',
                'purchase_products.unit_cost_exc_tax',
                'purchase_products.unit_discount_amount',
                'purchase_products.unit_tax_percent',
                'purchase_products.unit_tax_amount',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity',
                'units.code_name as unit_code',
                'purchase_products.line_total',
                'purchases.id',
                'purchases.branch_id',
                'purchases.supplier_account_id',
                'purchases.date',
                'purchases.invoice_id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'suppliers.name as supplier_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
            )->orderBy('purchases.report_date', 'desc');

            return DataTables::of($purchaseProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->name, 35, '') . $variant;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->branch_area_name . ')';
                        }
                    } else {

                        return $generalSettings['business__shop_name'];
                    }
                })
                ->editColumn('quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->quantity) . '/<span class="quantity" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('invoice_id', fn ($row) => '<a href="' . route('purchases.show', [$row->purchase_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->invoice_id . '</a>')

                ->editColumn('unit_cost_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_exc_tax))
                ->editColumn('unit_discount_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_discount_amount))
                ->editColumn('unit_tax_amount', fn ($row) => '(' . \App\Utils\Converter::format_in_bdt($row->unit_tax_percent) . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount))
                ->editColumn('net_unit_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_unit_cost))

                ->editColumn('line_total', fn ($row) => '<span class="line_total" data-value="' . $row->line_total . '">' . \App\Utils\Converter::format_in_bdt($row->line_total) . '</span>')

                ->rawColumns(['product', 'product_code', 'date', 'branch', 'quantity', 'invoice_id', 'unit_cost_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'net_unit_cost', 'line_total'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchased_product_report.index', compact('branches', 'supplierAccounts', 'ownBranchIdOrParentBranchId'));
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
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $purchaseProducts = '';
        $query = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'purchase_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id');

        if ($request->product_id) {
            $query->where('purchase_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {
            $query->where('purchase_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('purchases.branch_id', null);
            } else {

                $query->where('purchases.branch_id', $request->branch_id);
            }
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            //$date_range = [$fromDate . ' 00:00:00', $toDate . ' 00:00:00'];
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range);
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('purchases.branch_id', auth()->user()->branch_id);
        }

        $purchaseProducts = $query->select(
            'purchase_products.purchase_id',
            'purchase_products.product_id',
            'purchase_products.variant_id',
            'purchase_products.unit_cost_exc_tax',
            'purchase_products.unit_discount_amount',
            'purchase_products.unit_tax_percent',
            'purchase_products.unit_tax_amount',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity',
            'purchase_products.line_total',
            'units.code_name as unit_code',
            'purchases.id',
            'purchases.branch_id',
            'purchases.supplier_account_id',
            'purchases.date',
            'purchases.report_date',
            'purchases.invoice_id',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'suppliers.name as supplier_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('purchases.report_date', 'desc')->get();

        return view('purchase.reports.purchased_product_report.ajax_view.print', compact('purchaseProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredSupplierName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
