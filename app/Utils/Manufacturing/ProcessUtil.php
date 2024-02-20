<?php

namespace App\Utils\Manufacturing;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProcessUtil
{
    public function processTable($request)
    {
        $generalSettings = config('generalSettings');
        $process = DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subCate', 'products.sub_category_id', 'subCate.id')
            ->leftJoin('units', 'processes.unit_id', 'units.id')
            ->select(
                'processes.*',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'categories.name as cate_name',
                'subCate.name as sub_cate_name',
                'subCate.name as sub_cate_name',
                'units.name as unit_code',
            )->orderBy('processes.id', 'desc')->get();

        return DataTables::of($process)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.__('Action').'</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a id="view" class="dropdown-item" href="'.route('manufacturing.process.show', [$row->id]).'"> '.__('View').'</a>';

                if (auth()->user()->can('process_edit')) {

                    $html .= '<a class="dropdown-item" href="'.route('manufacturing.process.edit', [$row->id]).'"> '.__('Edit').'</a>';
                }

                if (auth()->user()->can('process_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('manufacturing.process.delete', [$row->id]).'">'.__('Delete').'</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('product', function ($row) {
                return $row->p_name.' '.$row->v_name;
            })
            ->editColumn('wastage_percent', function ($row) {
                return $row->wastage_percent.'%';
            })
            ->editColumn('total_output_qty', function ($row) {

                $wastage = $row->total_output_qty / 100 * $row->wastage_percent;
                $qtyWithWastage = $row->total_output_qty - $wastage;

                return bcadd($qtyWithWastage, 0, 2).' '.$row->unit_code;
            })
            ->editColumn('total_ingredient_cost', function ($row) {
                return $row->total_ingredient_cost;
            })
            ->editColumn('production_cost', function ($row) {
                return $row->production_cost;
            })
            ->editColumn('net_cost', function ($row) {
                return $row->total_cost;
            })
            ->rawColumns(['multiple_update', 'action', 'product', 'wastage_percent', 'total_output_qty', 'total_ingredient_cost', 'production_cost', 'total_cost'])
            ->make(true);
    }

    public function getProcessableProductForCreate($request)
    {
        $product = [];
        $productAndVariantId = explode('-', $request->product_id);
        $productId = $productAndVariantId[0];
        $variantId = $productAndVariantId[1];
        if ($variantId != 'noid') {

            $variantProduct = DB::table('product_variants')->where('product_variants.id', $variantId)
                ->leftJoin('products', 'product_variants.product_id', 'products.id')
                ->leftJoin('units', 'products.unit_id', 'units.id')
                ->select(
                    'product_variants.id as variant_id',
                    'product_variants.variant_name',
                    'product_variants.variant_code',
                    'products.id as product_id',
                    'products.name',
                    'products.id as unit_id',
                    'products.product_code',
                )->first();

            $product['product_id'] = $variantProduct->product_id;
            $product['unit_id'] = $variantProduct->unit_id;
            $product['product_name'] = $variantProduct->name;
            $product['product_code'] = $variantProduct->product_code;
            $product['variant_id'] = $variantProduct->variant_id;
            $product['variant_name'] = $variantProduct->variant_name;
            $product['variant_code'] = $variantProduct->variant_code;
        } else {

            $s_product = Product::with('unit')->where('id', $product_id)
                ->select('id', 'unit_id', 'name', 'product_code')
                ->first();

            $product['product_id'] = $s_product->id;
            $product['unit_id'] = $s_product->unit->id;
            $product['product_name'] = $s_product->name;
            $product['product_code'] = $s_product->product_code;
            $product['variant_id'] = null;
            $product['variant_name'] = null;
            $product['variant_code'] = null;
        }

        return $product;
    }
}
