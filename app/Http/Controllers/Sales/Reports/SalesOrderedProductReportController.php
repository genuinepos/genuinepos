<?php

namespace App\Http\Controllers\Sales\Reports;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;

class SalesOrderedProductReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if (!auth()->user()->can('sales_report')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {
            $generalSettings = config('generalSettings');
            $orderProducts = '';
            $query = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('units', 'sale_products.unit_id', 'units.id')
                ->leftJoin('categories', 'products.category_id', 'categories.id')
                ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
                ->where('sales.status', SaleStatus::Order->value);

            $this->filter(request: $request, query: $query);

            $orderProducts = $query->select(
                'sale_products.sale_id',
                'sale_products.product_id',
                'sale_products.variant_id',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_discount_amount',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.unit_price_inc_tax',
                'sale_products.ordered_quantity',
                'sale_products.subtotal',
                'units.code_name as unit_code',
                'sales.id',
                'sales.branch_id',
                'sales.customer_account_id',
                'sales.date',
                'sales.order_id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'customers.name as customer_name',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
            )->orderBy('sales.order_date_ts', 'desc');

            return DataTables::of($orderProducts)
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

                ->editColumn('ordered_quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->ordered_quantity) . '/<span class="quantity" data-value="' . $row->ordered_quantity . '">' . $row->unit_code . '</span>';
                })
                ->editColumn('order_id', fn ($row) => '<a href="' . route('sale.orders.show', [$row->sale_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->order_id . '</a>')

                ->editColumn('unit_price_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_price_exc_tax))
                ->editColumn('unit_discount_amount', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_discount_amount))
                ->editColumn('unit_tax_amount', fn ($row) => '(' . \App\Utils\Converter::format_in_bdt($row->unit_tax_percent) . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount))
                ->editColumn('unit_price_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax))

                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

                ->rawColumns(['product', 'product_code', 'date', 'branch', 'stock_location', 'ordered_quantity', 'order_id', 'unit_price_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'unit_price_inc_tax', 'subtotal'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('sales.reports.sales_ordered_products_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
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
        $filteredCustomerName = $request->customer_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $orderProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as sub_cate', 'products.sub_category_id', 'sub_cate.id')
            ->where('sales.status', SaleStatus::Order->value);

        $this->filter(request: $request, query: $query);

        $orderProducts = $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.variant_id',
            'sale_products.unit_price_exc_tax',
            'sale_products.unit_discount_amount',
            'sale_products.unit_tax_percent',
            'sale_products.unit_tax_amount',
            'sale_products.unit_price_inc_tax',
            'sale_products.ordered_quantity',
            'sale_products.subtotal',
            'units.code_name as unit_code',
            'sales.id',
            'sales.branch_id',
            'sales.customer_account_id',
            'sales.date',
            'sales.order_id',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
        )->orderBy('sales.order_date_ts', 'desc')->get();

        return view('sales.reports.sales_ordered_products_report.ajax_view.print', compact('orderProducts', 'fromDate', 'toDate', 'filteredBranchName', 'filteredCustomerName', 'filteredProductName', 'ownOrParentBranch'));
    }

    function filter($request, $query)
    {
        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.order_date_ts', $date_range);
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }
    }
}
