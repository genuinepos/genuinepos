<?php

namespace App\Http\Controllers\Products\Reports;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Utils\Converter;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;

class StockInOutReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $stockInOuts = '';
            $query = DB::table('purchase_sale_product_chains')
                ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
                ->leftJoin('products', 'sale_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
                ->leftJoin('branches', 'sales.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
                ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
                ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
                ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->leftJoin('transfer_stock_products', 'purchase_products.transfer_stock_product_id', 'transfer_stock_products.id')
                ->leftJoin('transfer_stocks', 'transfer_stock_products.transfer_stock_id', 'transfer_stocks.id')
                ->leftJoin('units', 'sale_products.unit_id', 'units.id');

            $this->filter(request: $request, query: $query);

            $stockInOuts = $query->select(
                'sales.id as sale_id',
                'sales.branch_id',
                'sales.date',
                'sales.sale_date_ts',
                'sales.invoice_id',
                'products.name',
                'products.created_at as product_created_at',
                'products.product_cost_with_tax as unit_cost_inc_tax',
                'product_variants.variant_name',
                'sale_products.unit_price_inc_tax',
                'units.name as unit_name',
                'purchase_sale_product_chains.sold_qty',
                'customers.name as customer_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.area_name as branch_area_name',
                'parentBranch.name as parent_branch_name',
                'purchases.id as purchase_id',
                'purchases.invoice_id as purchase_inv',
                'productions.id as production_id',
                'productions.voucher_no as production_voucher_no',
                'sale_returns.id as sale_return_id',
                'sale_returns.voucher_no as sales_return_voucher_no',
                'transfer_stocks.id as transfer_stock_id',
                'transfer_stocks.voucher_no as transfer_stock_voucher_no',
                'product_opening_stocks.id as product_opening_stock_id',
                'purchase_products.net_unit_cost',
                'purchase_products.quantity as stock_in_qty',
                'purchase_products.created_at as stock_in_date',
                'purchase_products.lot_no',

            )->orderBy('sales.sale_date_ts', 'desc');

            return DataTables::of($stockInOuts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->name, 20, '') . $variant;
                })

                ->editColumn('sale', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn" class="text-hover" title="view" >' . $row->invoice_id . '</a>')

                ->editColumn('date', function ($row) use ($generalSettings) {

                    return date($generalSettings['business_or_shop__date_format'], strtotime($row->sale_date_ts));
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

                ->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax) . '</span>')

                ->editColumn('sold_qty', function ($row) {

                    return '<span class="sold_qty" data-value="' . $row->sold_qty . '">' . \App\Utils\Converter::format_in_bdt($row->sold_qty) . '/' . $row->unit_name . '</span>';
                })

                ->editColumn('customer_name', function ($row) {

                    return $row->customer_name ? $row->customer_name : 'Walk-In-Customer';
                })

                ->editColumn('stock_in_by', function ($row) {

                    if ($row->purchase_inv) {

                        return __('Purchase') . ': <a href="' . route('purchases.show', [$row->purchase_id]) . '" id="details_btn" title="view" >' . $row->purchase_inv . '</a>';
                    } elseif ($row->production_voucher_no) {

                        return __('Production') . ': <a href="' . route('manufacturing.productions.show', [$row->production_id]) . '" id="details_btn" title="view">' . $row->production_voucher_no . '</a>';
                    } elseif ($row->product_opening_stock_id) {

                        return __('Opening Stock');
                    } elseif ($row->sale_return_id) {

                        return __('Sales Returned Stock') . ': <a href="' . route('sales.returns.show', [$row->production_id]) . '" id="details_btn" title="view" >' . $row->sales_return_voucher_no . '</a>';
                    } elseif ($row->transfer_stock_id) {

                        return __('Received Stock') . ': <a href="' . route('transfer.stocks.show', [$row->transfer_stock_id]) . '" id="details_btn" title="view" >' . $row->transfer_stock_voucher_no . '</a>';
                    } else {

                        return __('Non-Manageable-Stock');
                    }
                })

                ->editColumn('stock_in_date', function ($row) use ($generalSettings) {

                    if ($row->stock_in_date) {

                        return date($generalSettings['business_or_shop__date_format'], strtotime($row->stock_in_date));
                    } else {

                        return date($generalSettings['business_or_shop__date_format'], strtotime($row->product_created_at));
                    }
                })

                // ->editColumn('stock_in_qty', function ($row) {
                //     return '<span class="stock_in_qty" data-value="' . $row->stock_in_qty . '">' . $row->stock_in_qty . '</span>';
                // })

                ->editColumn('net_unit_cost', function ($row) {

                    if ($row->net_unit_cost) {

                        return '<span class="net_unit_cost" data-value="' . $row->net_unit_cost . '">' . \App\Utils\Converter::format_in_bdt($row->net_unit_cost) . '</span>';
                    } else {

                        return '<span class="net_unit_cost" data-value="' . $row->unit_cost_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax) . '</span>';
                    }
                })

                ->rawColumns(
                    ['product', 'sale', 'date', 'branch', 'unit_price_inc_tax', 'sold_qty', 'customer_name', 'stock_in_by', 'stock_in_date', 'net_unit_cost']
                )->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('product.reports.stock_in_out_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
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

        $stockInOuts = '';
        $query = DB::table('purchase_sale_product_chains')
            ->leftJoin('sale_products', 'purchase_sale_product_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('purchase_products', 'purchase_sale_product_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
            ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
            ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->leftJoin('transfer_stock_products', 'purchase_products.transfer_stock_product_id', 'transfer_stock_products.id')
            ->leftJoin('transfer_stocks', 'transfer_stock_products.transfer_stock_id', 'transfer_stocks.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id');

        $this->filter(request: $request, query: $query);

        $stockInOuts = $query->select(
            'sales.id as sale_id',
            'sales.branch_id',
            'sales.date',
            'sales.sale_date_ts',
            'sales.invoice_id',
            'products.name',
            'products.created_at as product_created_at',
            'products.product_cost_with_tax as unit_cost_inc_tax',
            'product_variants.variant_name',
            'sale_products.unit_price_inc_tax',
            'units.name as unit_name',
            'purchase_sale_product_chains.sold_qty',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name as branch_area_name',
            'parentBranch.name as parent_branch_name',
            'purchases.id as purchase_id',
            'purchases.invoice_id as purchase_inv',
            'productions.id as production_id',
            'productions.voucher_no as production_voucher_no',
            'sale_returns.id as sale_return_id',
            'sale_returns.voucher_no as sales_return_voucher_no',
            'transfer_stocks.id as transfer_stock_id',
            'transfer_stocks.voucher_no as transfer_stock_voucher_no',
            'product_opening_stocks.id as product_opening_stock_id',
            'purchase_products.net_unit_cost',
            'purchase_products.quantity as stock_in_qty',
            'purchase_products.created_at as stock_in_date',
            'purchase_products.lot_no',
        )->orderBy('sales.sale_date_ts', 'desc')->get();

        return view(
            'product.reports.stock_in_out_report.ajax_view.print',
            compact(
                'stockInOuts',
                'ownOrParentBranch',
                'filteredBranchName',
                'filteredCustomerName',
                'filteredProductName',
                'fromDate',
                'toDate',
            )
        );
    }

    private function filter(object $request, object $query): object
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

            if ($request->customer_id == 'NULL') {

                $query->where('sales.customer_account_id', null);
            } else {

                $query->where('sales.customer_account_id', $request->customer_account_id);
            }
        }

        if ($request->from_date) {

            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $fromDate;
            $date_range = [Carbon::parse($fromDate), Carbon::parse($toDate)->endOfDay()];
            $query->whereBetween('sales.sale_date_ts', $date_range);
        }

        if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
