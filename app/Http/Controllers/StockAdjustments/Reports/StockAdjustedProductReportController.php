<?php

namespace App\Http\Controllers\StockAdjustments\Reports;

use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustedProductReportController extends Controller
{
    public function __construct(private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('stock_adjustment_report'), 403);

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $adjustmentProducts = '';
            $query = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('warehouses', 'stock_adjustment_products.warehouse_id', 'warehouses.id')
                ->leftJoin('products', 'stock_adjustment_products.product_id', 'products.id')
                ->leftJoin('product_variants', 'stock_adjustment_products.variant_id', 'product_variants.id')
                ->leftJoin('units', 'stock_adjustment_products.unit_id', 'units.id');

            $this->filter(request: $request, query: $query);

            $adjustmentProducts = $query->select(
                'stock_adjustment_products.stock_adjustment_id',
                'stock_adjustment_products.product_id',
                'stock_adjustment_products.variant_id',
                'stock_adjustment_products.quantity',
                'stock_adjustment_products.unit_cost_inc_tax',
                'stock_adjustment_products.subtotal',
                'units.code_name as unit_code',
                'stock_adjustments.id',
                'stock_adjustments.branch_id',
                'stock_adjustments.date',
                'stock_adjustments.voucher_no',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'product_variants.variant_price',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
            )->orderBy('stock_adjustments.date_ts', 'desc');

            return DataTables::of($adjustmentProducts)
                ->editColumn('product', function ($row) {

                    $variant = $row->variant_name ? ' - ' . $row->variant_name : '';

                    return Str::limit($row->name, 35, '') . $variant;
                })
                ->editColumn('date', function ($row) use ($generalSettings) {

                    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);

                    return date($__date_format, strtotime($row->date));
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
                ->editColumn('stock_location', function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return $row->warehouse_name . '(' . $row->warehouse_code . ')';
                    } else {

                        if ($row->branch_id) {

                            if ($row->parent_branch_name) {

                                return $row->parent_branch_name . '(' . $row->branch_area_name . ')';
                            } else {

                                return $row->branch_name . '(' . $row->branch_area_name . ')';
                            }
                        } else {

                            return $generalSettings['business_or_shop__business_name'];
                        }
                    }
                })

                ->editColumn('voucher_no', fn ($row) => '<a href="' . route('stock.adjustments.show', [$row->stock_adjustment_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->voucher_no . '</a>')

                ->editColumn('quantity', function ($row) {

                    return \App\Utils\Converter::format_in_bdt($row->quantity) . '/<span class="quantity" data-value="' . $row->quantity . '">' . $row->unit_code . '</span>';
                })

                ->editColumn('unit_cost_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax))

                ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

                ->rawColumns(['product', 'date', 'branch', 'stock_location', 'quantity', 'voucher_no', 'unit_cost_inc_tax', 'subtotal'])
                ->make(true);
        }

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('stock_adjustments.reports.stock_adjusted_products_reports.index', compact('branches'));
    }

    public function print(Request $request)
    {
        $ownOrParentBranch = '';
        $branchName = $this->branchService->branchName();

        $filteredBranchName = $request->branch_name;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $adjustmentProducts = '';
        $query = DB::table('stock_adjustment_products')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
            ->leftJoin('branches', 'stock_adjustments.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'stock_adjustment_products.warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'stock_adjustment_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'stock_adjustment_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'stock_adjustment_products.unit_id', 'units.id');

        $this->filter(request: $request, query: $query);

        $adjustmentProducts = $query->select(
            'stock_adjustment_products.stock_adjustment_id',
            'stock_adjustment_products.product_id',
            'stock_adjustment_products.variant_id',
            'stock_adjustment_products.quantity',
            'stock_adjustment_products.unit_cost_inc_tax',
            'stock_adjustment_products.subtotal',
            'units.code_name as unit_code',
            'stock_adjustments.id',
            'stock_adjustments.branch_id',
            'stock_adjustments.date',
            'stock_adjustments.voucher_no',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
        )->orderBy('stock_adjustments.date_ts', 'desc')->get();

        return view('stock_adjustments.reports.stock_adjusted_products_reports.ajax_view.print', compact('adjustmentProducts', 'ownOrParentBranch', 'filteredBranchName', 'fromDate', 'toDate'));
    }

    private function filter(object $request, object $query): object
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('stock_adjustments.branch_id', null);
            } else {

                $query->where('stock_adjustments.branch_id', $request->branch_id);
            }
        }

        if ($request->type) {

            $query->where('stock_adjustments.type', $request->type);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('stock_adjustments.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('stock_adjustments.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
