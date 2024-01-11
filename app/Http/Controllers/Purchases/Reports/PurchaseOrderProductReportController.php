<?php

namespace App\Http\Controllers\Purchases\Reports;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountFilterService;
use App\Services\Accounts\AccountService;
use App\Services\Setups\BranchService;
use App\Services\Setups\WarehouseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderProductReportController extends Controller
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
        if (!auth()->user()->can('purchase_ordered_product_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $purchaseOrderProducts = '';
            $query = DB::table('purchase_order_products')
                ->leftJoin('purchases', 'purchase_order_products.purchase_id', '=', 'purchases.id')
                ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('products', 'purchase_order_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'purchase_order_products.variant_id', 'product_variants.id')
                ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
                ->leftJoin('units', 'purchase_order_products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id');

            if ($request->product_id) {

                $query->where('purchase_order_products.product_id', $request->product_id);
            }

            if ($request->variant_id) {

                $query->where('purchase_order_products.variant_id', $request->variant_id);
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

            $purchaseOrderProducts = $query->select(
                'purchase_order_products.purchase_id',
                'purchase_order_products.product_id',
                'purchase_order_products.variant_id',
                'purchase_order_products.unit_cost_exc_tax',
                'purchase_order_products.unit_discount_amount',
                'purchase_order_products.unit_tax_percent',
                'purchase_order_products.unit_tax_amount',
                'purchase_order_products.net_unit_cost',
                'purchase_order_products.ordered_quantity',
                'purchase_order_products.received_quantity',
                'purchase_order_products.pending_quantity',
                'purchase_order_products.line_total',
                'units.code_name as unit_code',
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

            return DataTables::of($purchaseOrderProducts)
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

                        return $generalSettings['business_or_shop__business_name'];
                    }
                })
                ->editColumn('ordered_quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->ordered_quantity) . '/<span class="ordered_quantity" data-value="' . $row->ordered_quantity . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('received_quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->received_quantity) . '/<span class="received_quantity" data-value="' . $row->received_quantity . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('pending_quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->pending_quantity) . '/<span class="pending_quantity" data-value="' . $row->pending_quantity . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('invoice_id', fn ($row) => '<a href="' . route('purchase.orders.show', [$row->purchase_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->invoice_id . '</a>')

                ->editColumn('unit_cost_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_exc_tax))
                ->editColumn('unit_discount_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_discount_amount))
                ->editColumn('unit_tax_amount', fn ($row) => '(' . \App\Utils\Converter::format_in_bdt($row->unit_tax_percent) . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount))
                ->editColumn('net_unit_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_unit_cost))

                ->editColumn('line_total', fn ($row) => '<span class="line_total" data-value="' . $row->line_total . '">' . \App\Utils\Converter::format_in_bdt($row->line_total) . '</span>')

                ->rawColumns(['product', 'product_code', 'date', 'branch', 'ordered_quantity', 'received_quantity', 'pending_quantity', 'invoice_id', 'unit_cost_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'net_unit_cost', 'line_total'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $supplierAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('purchase.reports.purchase_ordered_products_report.index', compact('branches', 'supplierAccounts', 'ownBranchIdOrParentBranchId'));
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

        $purchaseOrderProducts = '';
        $query = DB::table('purchase_order_products')
            ->leftJoin('purchases', 'purchase_order_products.purchase_id', '=', 'purchases.id')
            ->leftJoin('branches', 'purchases.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'purchase_order_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'purchase_order_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('units', 'purchase_order_products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id');

        if ($request->product_id) {

            $query->where('purchase_order_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('purchase_order_products.variant_id', $request->variant_id);
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

        $purchaseOrderProducts = $query->select(
            'purchase_order_products.purchase_id',
            'purchase_order_products.product_id',
            'purchase_order_products.variant_id',
            'purchase_order_products.unit_cost_exc_tax',
            'purchase_order_products.unit_discount_amount',
            'purchase_order_products.unit_tax_percent',
            'purchase_order_products.unit_tax_amount',
            'purchase_order_products.net_unit_cost',
            'purchase_order_products.ordered_quantity',
            'purchase_order_products.received_quantity',
            'purchase_order_products.pending_quantity',
            'purchase_order_products.line_total',
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

        return view('purchase.reports.purchase_ordered_products_report.ajax_view.print', compact('purchaseOrderProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredSupplierName', 'filteredProductName', 'ownOrParentBranch'));
    }
}
