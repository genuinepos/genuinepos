<?php

namespace App\Http\Controllers\Manufacturing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $branches = DB::table('branches')->select('id', 'name', 'branch_code')->get();
        return view('manufacturing.production.index', compact('branches'));
    }

    public function create()
    {
        $warehouses = DB::table('warehouses')->select('id', 'warehouse_name', 'warehouse_code')->get();
        $products =  DB::table('processes')
        ->leftJoin('products', 'processes.product_id', 'products.id')
        ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
        ->select(
            'processes.id',
            'processes.total_output_qty',
            'processes.unit_id',
            'products.id as p_id',
            'products.name as p_name',
            'products.product_code as p_code',
            'product_variants.id as v_id',
            'product_variants.variant_name as v_name',
            'product_variants.variant_code as v_code',
        )
        ->get();
        return view('manufacturing.production.create', compact('warehouses', 'products'));
    }

    public function getProcess($processId)
    {
        $process = DB::table('processes')
        ->leftJoin('units', 'processes.unit_id', 'units.id')
        ->select('processes.*', 'units.name as u_name')
        ->where('processes.id', $processId)->first();
        return response()->json($process);
    }

    public function getIngredients($processId)
    {
        $ingredients = DB::table('process_ingredients')
            ->leftJoin('products', 'process_ingredients.product_id', 'products.id')
            ->leftJoin('product_variants', 'process_ingredients.variant_id', 'product_variants.id')
            ->leftJoin('units', 'process_ingredients.unit_id', 'units.id')
            ->where('process_ingredients.process_id', $processId)
            ->select(
                'process_ingredients.*',
                'products.id as p_id',
                'products.name as p_name',
                'products.product_code as p_code',
                'product_variants.id as v_id',
                'product_variants.variant_name as v_name',
                'product_variants.variant_code as v_code',
                'units.id as u_id',
                'units.name as u_name',
            )->get();

        return view('manufacturing.production.ajax_view.ingredient_list', compact('ingredients'));
    }
}
