<?php

namespace App\Utils\Manufacturing;

use App\Models\Product;
use App\Utils\Converter;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductionUtil
{
    protected $converter;
    public function __construct(
        Converter $converter
    ) {
        $this->converter = $converter;
    }

    public function productionList($request)
    {
        $generalSettings = DB::table('general_settings')->first();
        $productions = '';
        $query = DB::table('productions')
            ->leftJoin('branches', 'productions.branch_id', 'branches.id')
            ->leftJoin('warehouses', 'productions.warehouse_id', 'warehouses.id')
            ->leftJoin('products', 'productions.product_id', 'products.id')
            ->leftJoin('product_variants', 'productions.variant_id', 'product_variants.id')
            ->leftJoin('units', 'productions.unit_id', 'units.id');

        if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) {
            $productions = $this->filteredQuery($request, $query)->select(
                'productions.*',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'units.code_name as u_name',
            )->orderBy('productions.report_date', 'desc');
        } else {
            $productions = $this->filteredQuery($request, $query)->select(
                'productions.*',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'units.code_name as u_name',
            )->where('productions.branch_id', auth()->user()->branch_id)
                ->orderBy('productions.report_date', 'desc');
        }

        return DataTables::of($productions)
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item details_button" href="' . route('manufacturing.productions.show', [$row->id]) . '"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                if (auth()->user()->branch_id == $row->branch_id) {
                    if (auth()->user()->permission->manufacturing['menuf_edit'] == '1') {
                        $html .= '<a class="dropdown-item" href="' . route('manufacturing.productions.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->permission->manufacturing['menuf_delete'] == '1') {
                        $html .= '<a class="dropdown-item" id="delete" href="' . route('manufacturing.productions.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                }

                $html .= '<a class="dropdown-item" id="send_notification" href="#"><i class="fas fa-envelope text-primary"></i> Send Notification</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {
                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })
            ->editColumn('from',  function ($row) use ($generalSettings) {
                if ($row->branch_name) {
                    return $row->branch_name . '/' . $row->branch_code . '(<b>BL</b>)';
                } else {
                    return json_decode($generalSettings->business, true)['shop_name'] . '(<b>HO</b>)';
                }
            })->editColumn('product',  fn ($row) => Str::limit($row->p_name, 25, '') . ' ' . $row->v_name)
            ->editColumn('unit_cost_inc_tax', fn ($row) => $this->converter->format_in_bdt($row->unit_cost_inc_tax))
            ->editColumn('price_exc_tax', fn ($row) => $this->converter->format_in_bdt($row->price_exc_tax))
            ->editColumn('total_final_quantity', fn ($row) => '<span class="total_final_quantity" data-value="' . $row->total_final_quantity . '">' . $row->total_final_quantity . '/' . $row->u_name . '</span>')
            ->editColumn('total_ingredient_cost', fn ($row) =>  '<span class="total_ingredient_cost" data-value="' . $row->total_ingredient_cost . '">' . $this->converter->format_in_bdt($row->total_ingredient_cost) . '</span>')
            ->editColumn('production_cost', fn ($row) => '<span class="production_cost" data-value="' . $row->production_cost . '">' . $this->converter->format_in_bdt($row->production_cost) . '</span>')
            ->editColumn('total_cost', fn ($row) => '<span class="total_cost" data-value="' . $row->total_cost . '">' . $this->converter->format_in_bdt($row->total_cost) . '</span>')
            ->editColumn('status', function ($row) {
                if ($row->is_final == 1) {
                    return '<span class="text-success"><b>Final</b></span>';
                } else {
                    return '<span class="text-danger"><b>Hold</b></span>';
                }
            })
            ->rawColumns(['action', 'date', 'from', 'product', 'unit_cost_inc_tax', 'price_exc_tax', 'total_final_quantity', 'total_ingredient_cost', 'production_cost', 'total_cost', 'status'])
            ->make(true);
    }

    public function updateProductAndVariantPriceByProduction(
        $productId,
        $variant_id,
        $unit_cost_exc_tax,
        $unit_cost_inc_tax,
        $x_margin,
        $selling_price,
        $tax_id,
        $tax_type
    ) {
        $updateProduct = Product::where('id', $productId)->first();
        $updateProduct->is_purchased = 1;
        $updateProduct->tax_id = $tax_id;
        $updateProduct->tax_type = $tax_type;
        if ($updateProduct->is_variant == 0) {
            $updateProduct->product_cost =  $unit_cost_exc_tax;
            $updateProduct->product_cost_with_tax = $unit_cost_inc_tax;
            $updateProduct->profit = $x_margin;
            $updateProduct->product_price = $selling_price;
        }
        $updateProduct->save();

        if ($variant_id != NULL) {
            $updateVariant = ProductVariant::where('id', $variant_id)
                ->where('product_id', $productId)
                ->first();
            $updateVariant->variant_cost = $unit_cost_exc_tax;
            $updateVariant->variant_cost_with_tax = $unit_cost_inc_tax;
            $updateVariant->variant_profit = $x_margin;
            $updateVariant->variant_price = $selling_price;
            $updateVariant->is_purchased = 1;
            $updateVariant->save();
        }
    }

    private function filteredQuery($request, $query)
    {
        if ($request->branch_id) {
            if ($request->branch_id == 'NULL') {
                $query->where('productions.branch_id', NULL);
            } else {
                $query->where('productions.branch_id', $request->branch_id);
            }
        }

        if ($request->status) {
            $query->where('productions.is_final', $request->status);
        }

        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $query->whereBetween('sales.report_date', $date_range); // Final
        }
        return $query;
    }
}
