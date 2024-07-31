<?php

namespace App\Services\Manufacturing\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\ProductionStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IngredientReportService
{
    public function ingredientReportTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $ingredients = $this->query(request: $request);

        return DataTables::of($ingredients)
            ->editColumn('date', fn ($row) => date($generalSettings['business_or_shop__date_format'], strtotime($row->date)))
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business_or_shop__business_name'];
                }
            })
            ->editColumn('stock_location', function ($row) use ($generalSettings) {

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

                        return $generalSettings['business_or_shop__business_name'];
                    }
                }
            })
            ->editColumn('voucher_no', fn ($row) => '<a href="' . route('manufacturing.productions.show', [$row->production_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->voucher_no . '</a>')
            ->editColumn('mfd_product', function ($row) {

                $variantName = $row->mfd_variant_name ? ' _ ' . $row->mfd_variant_name : '';
                $productCode = $row->mfd_variant_code ? ' (' . $row->mfd_variant_code . ')' : ' (' . $row->mfd_product_code . ')';

                return Str::limit($row->mfd_product_name, 35, '') . $variantName . $productCode;
            })
            ->editColumn('ingredient_product', function ($row) {

                $variantName = $row->ingredient_variant_name ? ' _ ' . $row->ingredient_variant_name : '';
                $productCode = $row->ingredient_variant_code ? ' (' . $row->ingredient_variant_code . ')' : ' (' . $row->ingredient_product_code . ')';

                return Str::limit($row->ingredient_product_name, 35, '') . $variantName . $productCode;
            })

            ->editColumn('unit_cost_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_exc_tax))

            ->editColumn('unit_tax_amount', fn ($row) => '(' . $row->unit_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount))

            ->editColumn('unit_cost_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax))

            ->editColumn('final_qty', fn ($row) => '<span class="final_qty" data-value="' . $row->final_qty . '">' . \App\Utils\Converter::format_in_bdt($row->final_qty) . '/' . $row->unit_name . '</span>')

            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

            ->editColumn('status', function ($row) {
                if ($row->status == ProductionStatus::Final->value) {

                    return '<span class="text-success">' . __('Final') . '</span>';
                } else {

                    return '<span class="text-danger">' . __('Hold') . '</span>';
                }
            })
            ->rawColumns(['date', 'voucher_no', 'branch', 'mfd_product', 'ingredient_product', 'unit_cost_inc_tax', 'unit_cost_exc_tax', 'unit_tax_amount', 'unit_cost_inc_tax', 'final_qty', 'subtotal', 'status'])
            ->make(true);
    }

    public function query(object $request): object
    {
        $query = DB::table('production_ingredients')
            ->leftJoin('productions', 'production_ingredients.production_id', 'productions.id')
            ->leftJoin('branches', 'productions.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('warehouses', 'productions.stock_warehouse_id', 'warehouses.id')
            ->leftJoin('products as mfd_product', 'productions.product_id', 'mfd_product.id')
            ->leftJoin('product_variants as mfd_variant', 'productions.variant_id', 'mfd_variant.id')
            ->leftJoin('products as ingredient_product', 'production_ingredients.product_id', 'ingredient_product.id')
            ->leftJoin('product_variants as ingredient_variant', 'production_ingredients.variant_id', 'ingredient_variant.id')
            ->leftJoin('units', 'production_ingredients.unit_id', 'units.id');

        $this->filter(request: $request, query: $query);

        return $query->select(
            'production_ingredients.*',
            'productions.branch_id',
            'productions.date',
            'productions.voucher_no',
            'productions.status',
            'mfd_product.name as mfd_product_name',
            'mfd_product.product_code as mfd_product_code',
            'mfd_variant.variant_name as mfd_variant_name',
            'mfd_variant.variant_code as mfd_variant_code',
            'ingredient_product.name as ingredient_product_name',
            'ingredient_product.product_code as ingredient_product_code',
            'ingredient_variant.variant_name as ingredient_variant_name',
            'ingredient_variant.variant_code as ingredient_variant_code',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'units.code_name as unit_name',
        )->orderBy('productions.date_ts', 'desc');
    }

    private function filter(object $request, object $query): object
    {
        if ($request->product_id) {

            $query->where('production_ingredients.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('production_ingredients.variant_id', $request->variant_id);
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

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('productions.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
