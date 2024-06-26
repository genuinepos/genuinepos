<?php

namespace App\Http\Controllers\Products\Reports;

use Carbon\Carbon;
use App\Utils\Converter;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\SaleScreenType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\AccountFilterService;
use App\Http\Requests\Products\Reports\StockInOutReportIndexRequest;
use App\Http\Requests\Products\Reports\StockInOutReportPrintRequest;

class StockInOutReportController extends Controller
{
    public function __construct(
        private AccountService $accountService,
        private AccountFilterService $accountFilterService,
        private BranchService $branchService,
    ) {
    }

    public function index(StockInOutReportIndexRequest $request)
    {
        if ($request->ajax()) {

            $generalSettings = config('generalSettings');

            $stockInOuts = $this->stockInOutQuery(request: $request);

            return DataTables::of($stockInOuts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                    return Str::limit($row->product_name, 25, '') . $variant;
                })

                ->editColumn('stock_out_by', function ($row) {

                    if ($row->sale_id) {

                        return __('Sales') . ': <a href="' . route('sales.show', [$row->sale_id]) . '" id="details_btn" title="view" >' . $row->invoice_id . '</a>';
                    } elseif ($row->stock_issue_id) {

                        return __('Stock Issue') . ': <a href="' . route('stock.issues.show', [$row->stock_issue_id]) . '" id="details_btn" title="view">' . $row->stock_issue_voucher_no . '</a>';
                    } elseif ($row->stock_adjustment_id) {

                        return __('Stock Adjustment') . ': <a href="' . route('stock.adjustments.show', [$row->stock_adjustment_id]) . '" id="details_btn" title="view" >' . $row->stock_adjustment_voucher_no . '</a>';
                    }
                })

                ->editColumn('stock_out_date', function ($row) use ($generalSettings) {

                    return date($generalSettings['business_or_shop__date_format'], strtotime($row->stock_out_data_ts));
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

                ->editColumn('stock_out_unit_cost_or_price_inc_tax', function ($row) {

                    if ($row->sale_id) {

                        return \App\Utils\Converter::format_in_bdt($row->sale_unit_price_inc_tax);
                    } elseif ($row->stock_issue_id) {

                        return \App\Utils\Converter::format_in_bdt($row->stock_issue_unit_cost_inc_tax);
                    } elseif ($row->stock_adjustment_id) {

                        return \App\Utils\Converter::format_in_bdt($row->stock_adjustment_unit_cost_inc_tax);
                    }
                })

                ->editColumn('out_qty', function ($row) {

                    $stockOutUnit = null;
                    if ($row->sale_id) {

                        $stockOutUnit = $row->sale_unit;
                    } elseif ($row->stock_issue_id) {

                        $stockOutUnit = $row->stock_issue_unit;
                    } elseif ($row->stock_adjustment_id) {

                        $stockOutUnit = $row->stock_adjustment_unit;
                    }

                    return '<span class="out_qty" data-value="' . $row->out_qty . '">' . \App\Utils\Converter::format_in_bdt($row->out_qty) . '/' . $stockOutUnit . '</span>';
                })

                ->editColumn('customer_name', function ($row) {

                    return $row->customer_name ? $row->customer_name : '';
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

                    if ($row->stock_in_date_ts) {

                        return date($generalSettings['business_or_shop__date_format'], strtotime($row->stock_in_date_ts));
                    } else {

                        return date($generalSettings['business_or_shop__date_format'], strtotime($row->product_created_at));
                    }
                })

                // ->editColumn('stock_in_qty', function ($row) {
                //     return '<span class="stock_in_qty" data-value="' . $row->stock_in_qty . '">' . $row->stock_in_qty . '</span>';
                // })

                ->editColumn('stock_in_unit_cost_inc_tax', function ($row) {

                    if ($row->stock_in_unit_cost_inc_tax) {

                        return '<span class="stock_in_unit_cost_inc_tax" data-value="' . $row->stock_in_unit_cost_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->stock_in_unit_cost_inc_tax) . '</span>';
                    } else {

                        if ($row->variant_unit_cost_inc_tax) {

                            return '<span class="stock_in_unit_cost_inc_tax" data-value="' . $row->variant_unit_cost_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->variant_unit_cost_inc_tax) . '</span>';
                        } else {

                            return '<span class="stock_in_unit_cost_inc_tax" data-value="' . $row->product_unit_cost_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->product_unit_cost_inc_tax) . '</span>';
                        }
                    }
                })
                ->rawColumns(
                    ['product', 'stock_out_by', 'stock_out_date', 'branch', 'stock_out_unit_cost_or_price_inc_tax', 'out_qty', 'customer_name', 'stock_in_by', 'stock_in_date', 'stock_in_unit_cost_inc_tax']
                )->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $customerAccounts = $this->accountService->customerAndSupplierAccounts($ownBranchIdOrParentBranchId);

        return view('product.reports.stock_in_out_report.index', compact('branches', 'customerAccounts', 'ownBranchIdOrParentBranchId'));
    }

    public function print(StockInOutReportPrintRequest $request)
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

        $stockInOuts = $this->stockInOutQuery(request: $request)->get();

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

    private function stockInOutQuery(object $request): object
    {
        $query = DB::table('stock_chains')
            ->leftJoin('sale_products', 'stock_chains.sale_product_id', 'sale_products.id')
            ->leftJoin('units as saleUnit', 'sale_products.unit_id', 'saleUnit.id')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')

            ->leftJoin('stock_issue_products', 'stock_chains.stock_issue_product_id', 'stock_issue_products.id')
            ->leftJoin('units as stockIssueUnit', 'stock_issue_products.unit_id', 'stockIssueUnit.id')
            ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')

            ->leftJoin('stock_adjustment_products', 'stock_chains.stock_adjustment_product_id', 'stock_adjustment_products.id')
            ->leftJoin('units as stockAdjustmentUnit', 'stock_adjustment_products.unit_id', 'stockAdjustmentUnit.id')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')

            ->leftJoin('branches', 'stock_chains.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')

            ->leftJoin('products', 'stock_chains.product_id', 'products.id')
            ->leftJoin('product_variants', 'stock_chains.variant_id', 'product_variants.id')

            ->leftJoin('purchase_products', 'stock_chains.purchase_product_id', 'purchase_products.id')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->leftJoin('productions', 'purchase_products.production_id', 'productions.id')
            ->leftJoin('product_opening_stocks', 'purchase_products.opening_stock_id', 'product_opening_stocks.id')
            ->leftJoin('sale_return_products', 'purchase_products.sale_return_product_id', 'sale_return_products.id')
            ->leftJoin('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->leftJoin('transfer_stock_products', 'purchase_products.transfer_stock_product_id', 'transfer_stock_products.id')
            ->leftJoin('transfer_stocks', 'transfer_stock_products.transfer_stock_id', 'transfer_stocks.id');

        $this->filter(request: $request, query: $query);

        $query->select(
            'sales.id as sale_id',
            'sales.invoice_id',
            'sale_products.unit_price_inc_tax as sale_unit_price_inc_tax',
            'saleUnit.code_name as sale_unit',
            'customers.name as customer_name',

            'stock_issues.id as stock_issue_id',
            'stock_issues.voucher_no as stock_issue_voucher_no',
            'stock_issue_products.unit_cost_inc_tax as stock_issue_unit_cost_inc_tax',
            'stockIssueUnit.code_name as stock_issue_unit',

            'stock_adjustments.id as stock_adjustment_id',
            'stock_adjustments.voucher_no as stock_adjustment_voucher_no',
            'stock_adjustment_products.unit_cost_inc_tax as stock_adjustment_unit_cost_inc_tax',
            'stockAdjustmentUnit.code_name as stock_adjustment_unit',

            'products.name as product_name',
            'products.created_at as product_created_at',
            'products.product_cost_with_tax as product_unit_cost_inc_tax',

            'product_variants.variant_name',
            'product_variants.variant_cost_with_tax as variant_unit_cost_inc_tax',

            'sale_products.unit_price_inc_tax as sale_price_inc_tax',

            'stock_chains.branch_id',
            'stock_chains.out_qty',
            'stock_chains.created_at as stock_out_data_ts',

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

            'purchase_products.net_unit_cost as stock_in_unit_cost_inc_tax',
            'purchase_products.quantity as stock_in_qty',
            'purchase_products.created_at as stock_in_date_ts',
            'purchase_products.lot_no',
        )->orderBy('stock_chains.created_at', 'desc');

        return $query;
    }

    private function filter(object $request, object $query): object
    {
        if ($request->product_id) {

            $query->where('stock_chains.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('stock_chains.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('stock_chains.branch_id', null);
            } else {

                $query->where('stock_chains.branch_id', $request->branch_id);
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
            $query->whereBetween('stock_chains.created_at', $date_range);
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('stock_chains.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
