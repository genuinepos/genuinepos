<?php

namespace App\Services\Manufacturing;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Enums\IsDeleteInUpdate;
use App\Enums\ProductionStatus;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturing\Production;
use Yajra\DataTables\Facades\DataTables;

class ProductionService
{
    function productionsTable(object $request): ?object
    {
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
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('manufacturing.productions.show', [$row->id]) . '"> ' . __("View") . '</a>';

                if (auth()->user()->branch_id == $row->branch_id) {

                    if (auth()->user()->can('production_edit')) {

                        $html .= '<a href="' . route('manufacturing.productions.edit', [$row->id]) . '" class="dropdown-item"> ' . __("Edit") . '</a>';
                    }

                    if (auth()->user()->can('production_delete')) {

                        $html .= '<a href="' . route('manufacturing.productions.delete', [$row->id]) . '" class="dropdown-item" id="delete"> ' . __("Delete") . '</a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
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
                $productCode = $row->variant_code ? ' (' . $row->variant_code.')' : ' ('.$row->product_code.')';
                return Str::limit($row->product_name, 35, '') . $variantName . $productCode;
            })

            ->editColumn('per_unit_cost_inc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->per_unit_cost_inc_tax))

            ->editColumn('per_unit_price_exc_tax', fn ($row) => \App\Utils\Converter::format_in_bdt($row->per_unit_price_exc_tax))

            ->editColumn('total_final_output_quantity', fn ($row) => '<span class="total_final_output_quantity" data-value="' . $row->total_final_output_quantity . '">' . \App\Utils\Converter::format_in_bdt($row->total_final_output_quantity) . '/' . $row->unit_name . '</span>')

            ->editColumn('total_ingredient_cost', fn ($row) => '<span class="total_ingredient_cost" data-value="' . $row->total_ingredient_cost . '">' . \App\Utils\Converter::format_in_bdt($row->total_ingredient_cost) . '</span>')

            ->editColumn('additional_production_cost', fn ($row) => '<span class="additional_production_cost" data-value="' . $row->additional_production_cost . '">' . \App\Utils\Converter::format_in_bdt($row->additional_production_cost) . '</span>')

            ->editColumn('net_cost', fn ($row) => '<span class="net_cost" data-value="' . $row->net_cost . '">' . \App\Utils\Converter::format_in_bdt($row->net_cost) . '</span>')

            ->editColumn('status', function ($row) {
                if ($row->status == ProductionStatus::Final->value) {

                    return '<span class="text-success">' . __("Final") . '</span>';
                } else {

                    return '<span class="text-danger">' . __("Hold") . '</span>';
                }
            })
            ->rawColumns(['action', 'date', 'voucher_no', 'branch', 'product', 'per_unit_cost_inc_tax', 'per_unit_price_exc_tax', 'total_final_output_quantity', 'total_ingredient_cost', 'additional_production_cost', 'net_cost', 'status'])
            ->make(true);
    }

    public function restrictions($request): array
    {

        if (!isset($request->product_ids)) {

            return ['' => false, 'msg' => __("Ingredients list must not be empty.")];
        }

        return ['pass' => true];
    }

    public function addProduction(object $request, object $codeGenerator, ?string $voucherPrefix): object
    {
        $updateLastEntry = $this->singleProduction()->where('is_last_entry', 1)->select('id', 'is_last_entry')->first();

        if ($updateLastEntry) {

            $updateLastEntry->is_last_entry = 0;
            $updateLastEntry->save();
        }

        $voucherNo = $codeGenerator->generateMonthWise(table: 'productions', column: 'voucher_no', prefix: $voucherPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addProduction = new Production();
        $addProduction->branch_id = auth()->user()->branch_id;
        $addProduction->store_warehouse_id = $request->store_warehouse_id;
        $addProduction->stock_warehouse_id = $request->stock_warehouse_id;
        $addProduction->process_id = $request->process_id;
        $addProduction->voucher_no = $voucherNo;
        $addProduction->date = $request->date;
        $addProduction->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addProduction->product_id = $request->product_id;
        $addProduction->variant_id = $request->variant_id == 'noid' ? null : $request->variant_id;
        $addProduction->unit_id = $request->unit_id;
        $addProduction->total_ingredient_cost = $request->total_ingredient_cost;
        $addProduction->total_output_quantity = $request->total_output_quantity;
        $addProduction->total_parameter_quantity = $request->total_parameter_quantity;
        $addProduction->total_wasted_quantity = $request->total_wasted_quantity;
        $addProduction->total_final_output_quantity = $request->total_final_output_quantity;
        $addProduction->additional_production_cost = $request->additional_production_cost;
        $addProduction->net_cost = $request->net_cost;
        $addProduction->tax_ac_id = $request->tax_ac_id;
        $addProduction->tax_type = $request->tax_type;
        $addProduction->unit_tax_percent = $request->unit_tax_percent;
        $addProduction->unit_tax_amount = $request->unit_tax_amount;
        $addProduction->per_unit_cost_exc_tax = $request->per_unit_cost_exc_tax;
        $addProduction->per_unit_cost_inc_tax = $request->per_unit_cost_inc_tax;
        $addProduction->profit_margin = $request->profit_margin;
        $addProduction->per_unit_price_exc_tax = $request->per_unit_price_exc_tax;
        $addProduction->status = $request->status;
        $addProduction->is_last_entry = 1;
        $addProduction->save();

        return $addProduction;
    }

    public function updateProduction(object $request, int $productionId) : object {

        $updateProduction = $this->singleProduction(with: ['ingredients'])->where('id', $productionId)->first();

        $previousProductId = $updateProduction->product_id;
        $previousVariantId = $updateProduction->variant_id;
        $previousStoreWarehouseId = $updateProduction->store_warehouse_id;
        $previousStockWarehouseId = $updateProduction->stock_warehouse_id;

        foreach ($updateProduction->ingredients as $ingredient) {

            $ingredient->is_delete_in_update = IsDeleteInUpdate::Yes->value;
            $ingredient->save();
        }

        $updateProduction->process_id = $request->process_id;
        $updateProduction->product_id = $request->product_id;
        $updateProduction->variant_id = $request->variant_id == 'noid' ? null : $request->variant_id;
        $updateProduction->unit_id = $request->unit_id;
        $updateProduction->store_warehouse_id = $request->store_warehouse_id;
        $updateProduction->stock_warehouse_id = $request->stock_warehouse_id;
        $updateProduction->date = $request->date;
        $time = date(' H:i:s', strtotime($updateProduction->date_ts));
        $updateProduction->date_ts = date('Y-m-d H:i:s', strtotime($request->date . $time));
        $updateProduction->total_ingredient_cost = $request->total_ingredient_cost;
        $updateProduction->total_output_quantity = $request->total_output_quantity;
        $updateProduction->total_parameter_quantity = $request->total_parameter_quantity;
        $updateProduction->total_wasted_quantity = $request->total_wasted_quantity;
        $updateProduction->total_final_output_quantity = $request->total_final_output_quantity;
        $updateProduction->additional_production_cost = $request->additional_production_cost;
        $updateProduction->net_cost = $request->net_cost;
        $updateProduction->tax_ac_id = $request->tax_ac_id;
        $updateProduction->tax_type = $request->tax_type;
        $updateProduction->unit_tax_percent = $request->unit_tax_percent;
        $updateProduction->unit_tax_amount = $request->unit_tax_amount;
        $updateProduction->per_unit_cost_exc_tax = $request->per_unit_cost_exc_tax;
        $updateProduction->per_unit_cost_inc_tax = $request->per_unit_cost_inc_tax;
        $updateProduction->profit_margin = $request->profit_margin;
        $updateProduction->per_unit_price_exc_tax = $request->per_unit_price_exc_tax;
        $updateProduction->status = $request->status;
        $updateProduction->save();

        $updateProduction->previous_product_id = $previousProductId;
        $updateProduction->previous_variant_id = $previousVariantId;
        $updateProduction->previous_store_warehouse_id = $previousStoreWarehouseId;
        $updateProduction->previous_stock_warehouse_id = $previousStockWarehouseId;

        return $updateProduction;
    }

    public function singleProduction(array $with = null): ?object
    {
        $query = Production::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    private function filter(object $request, object $query): object
    {
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
