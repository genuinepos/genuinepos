<?php

namespace App\Http\Controllers\Manufacturing\Reports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\ProductionStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Setups\BranchService;
use Yajra\DataTables\Facades\DataTables;

class ProductionReportController extends Controller
{
    public function __construct(private BranchService $branchService)
    {
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('manufacturing_report')) {

            return response()->json('Access Denied');
        }

        if ($request->ajax()) {

            $generalSettings = config('generalSettings');
            $productions = '';
            $query = DB::table('productions')
                ->leftJoin('branches', 'productions.branch_id', 'branches.id')
                ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
                ->leftJoin('warehouses', 'productions.store_warehouse_id', 'warehouses.id')
                ->leftJoin('products', 'productions.product_id', 'products.id')
                ->leftJoin('product_variants', 'productions.variant_id', 'product_variants.id')
                ->leftJoin('units', 'productions.unit_id', 'units.id');

            $this->filter(request: $request, query: $query);

            $productions = $query->select(
                'productions.*',
                'products.name as product_name',
                'products.product_code',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.area_name',
                'parentBranch.name as parent_branch_name',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'units.code_name as unit_name',
            )->orderBy('productions.date_ts', 'desc');

            return DataTables::of($productions)
                ->editColumn('date', fn ($row) => date($generalSettings['business__date_format'], strtotime($row->date)))
                ->editColumn('branch', function ($row) use ($generalSettings) {

                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            return $row->parent_branch_name . '(' . $row->area_name . ')';
                        } else {

                            return $row->branch_name . '(' . $row->area_name . ')';
                        }
                    } else {

                        return $generalSettings['business__shop_name'];
                    }
                })
                ->editColumn('store_location', function ($row) use ($generalSettings) {

                    if ($row->warehouse_name) {

                        return $row->warehouse_name . '-(' . $row->warehouse_code . ')';
                    } else {

                        if ($row->branch_id) {

                            if ($row->parent_branch_name) {

                                return $row->parent_branch_name . '(' . $row->area_name . ')';
                            } else {

                                return $row->branch_name . '(' . $row->area_name . ')';
                            }
                        } else {

                            return $generalSettings['business__shop_name'];
                        }
                    }
                })
                ->editColumn('voucher_no', fn ($row) => '<a href="' . route('manufacturing.productions.show', [$row->id]) . '" class="text-hover" id="details_btn" title="View">' . $row->voucher_no . '</a>')
                ->editColumn('product', function ($row) {

                    $variantName = $row->variant_name ? ' _ ' . $row->variant_name : '';
                    $productCode = $row->variant_code ? ' (' . $row->variant_code . ')' : ' (' . $row->product_code . ')';
                    return Str::limit($row->product_name, 35, '') . $variantName . $productCode;
                })

                ->editColumn('per_unit_cost_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->per_unit_cost_exc_tax))

                ->editColumn('unit_tax_amount', fn ($row) => '(' . $row->unit_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount))

                ->editColumn('per_unit_cost_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->per_unit_cost_inc_tax))

                ->editColumn('per_unit_price_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->per_unit_price_exc_tax))

                ->editColumn('total_output_quantity', fn ($row) => '<span class="total_output_quantity" data-value="' . $row->total_output_quantity . '">' . \App\Utils\Converter::format_in_bdt($row->total_output_quantity) . '/' . $row->unit_name . '</span>')

                ->editColumn('total_wasted_quantity', fn ($row) => '<span class="total_wasted_quantity" data-value="' . $row->total_wasted_quantity . '">' . \App\Utils\Converter::format_in_bdt($row->total_wasted_quantity) . '/' . $row->unit_name . '</span>')

                ->editColumn('total_final_output_quantity', fn ($row) => '<span class="total_final_output_quantity" data-value="' . $row->total_final_output_quantity . '">' . \App\Utils\Converter::format_in_bdt($row->total_final_output_quantity) . '/' . $row->unit_name . '</span>')

                ->editColumn('total_ingredient_cost', fn ($row) => '<span class="total_ingredient_cost" data-value="' . $row->total_ingredient_cost . '">' . \App\Utils\Converter::format_in_bdt($row->total_ingredient_cost) . '/' . $row->unit_name . '</span>')

                ->editColumn('additional_production_cost', fn ($row) => '<span class="additional_production_cost" data-value="' . $row->additional_production_cost . '">' . \App\Utils\Converter::format_in_bdt($row->additional_production_cost) . '</span>')

                ->editColumn('net_cost', fn ($row) => '<span class="net_cost" data-value="' . $row->net_cost . '">' . \App\Utils\Converter::format_in_bdt($row->net_cost) . '</span>')

                ->editColumn('status', function ($row) {
                    if ($row->status == ProductionStatus::Final->value) {

                        return '<span class="text-success">' . __("Final") . '</span>';
                    } else {

                        return '<span class="text-danger">' . __("Hold") . '</span>';
                    }
                })
                ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'product', 'per_unit_cost_inc_tax', 'per_unit_cost_exc_tax', 'unit_tax_amount', 'per_unit_price_exc_tax', 'total_output_quantity', 'total_wasted_quantity', 'total_final_output_quantity', 'total_ingredient_cost', 'additional_production_cost', 'net_cost', 'status'])
                ->make(true);
        }

        $ownBranchIdOrParentBranchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;

        $branches = $this->branchService->branches(with: ['parentBranch'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        return view('manufacturing.reports.production_report.index', compact('branches', 'ownBranchIdOrParentBranchId'));
    }

    public function print(Request $request)
    {
        if (!auth()->user()->can('manufacturing_report')) {

            return response()->json('Access Denied');
        }

        $ownOrParentBranch = '';
        if (auth()->user()?->branch) {

            if (auth()->user()?->branch->parentBranch) {

                $branchName = auth()->user()?->branch->parentBranch;
            } else {

                $branchName = auth()->user()?->branch;
            }
        }

        $filteredBranchName = $request->branch_name;
        $filteredStatusName = $request->status_name;
        $filteredProductName = $request->search_product;
        $fromDate = $request->from_date;
        $toDate = $request->to_date ? $request->to_date : $request->from_date;

        $productions = '';
        $query = DB::table('productions')
            ->leftJoin('branches', 'productions.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'productions.store_warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'productions.product_id', 'products.id')
            ->leftJoin('product_variants', 'productions.variant_id', 'product_variants.id')
            ->leftJoin('units', 'productions.unit_id', 'units.id');

        $this->filter(request: $request, query: $query);

        $productions = $query->select(
            'productions.*',
            'products.name as product_name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'units.code_name as unit_name',
        )->orderBy('productions.date_ts', 'desc')->get();

        return view('manufacturing.reports.production_report.ajax_view.print', compact('productions', 'fromDate', 'toDate', 'filteredBranchName', 'filteredStatusName', 'filteredProductName', 'ownOrParentBranch'));
    }

    private function filter($request, $query)
    {
        if ($request->product_id) {

            $query->where('productions.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('productions.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('productions.branch_id', null);
            } else {

                $query->where('productions.branch_id', $request->branch_id);
            }
        }


        if ($request->status != '') {

            $query->where('productions.status', $request->status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('productions.date_ts', $date_range); // Final
        }

        if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {

            $query->where('productions.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
