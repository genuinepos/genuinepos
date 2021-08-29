<?php

namespace App\Http\Controllers\Manufacturing;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Manufacturing\Process;
use App\Models\Manufacturing\ProcessIngredient;

class ProcessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_and_user');
    }

    // Process index view method
    public function index(Request $request)
    {
        $products = DB::table('products')
            ->where('status', 1)
            ->leftJoin('product_variants', 'products.id', 'product_variants.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.product_code',
                'products.product_price',
                'product_variants.id as v_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )
            ->get();

        if ($request->ajax()) {
            $generalSettings = DB::table('general_settings')->first();
            $process = DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subCate', 'products.parent_category_id', 'subCate.id')
            ->leftJoin('units', 'processes.unit_id', 'units.id')
            ->select(
                'processes.*',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'categories.name as cate_name',
                'subCate.name as sub_cate_name',
                'subCate.name as sub_cate_name',
                'units.name as u_name',
            )->get();

            return DataTables::of($process)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" href="' . route('manufacturing.process.show', [$row->id]) . '"><i class="far fa-eye text-primary"></i> View</a>';
                $html .= '<a class="dropdown-item" href="' . route('manufacturing.process.edit', [$row->id]) . '"><i class="far fa-edit text-primary"></i> Edit</a>';
                $html .= '<a class="dropdown-item" id="delete" href="' . route('manufacturing.process.delete', [$row->id]) . '"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('product', function ($row)
            {
                return $row->p_name.' '.$row->v_name;
            })
            ->editColumn('wastage_percent', function ($row) 
            {
                return $row->wastage_percent.' %';
            })
            ->editColumn('total_output_qty', function ($row) 
            {
                $wastage = $row->total_output_qty / 100 * $row->wastage_percent;
                $qtyWithWastage = $row->total_output_qty - $wastage;
                return bcadd($qtyWithWastage, 0, 2).' '.$row->u_name;
            })
            ->editColumn('total_ingredient_cost', function ($row) use ($generalSettings)
            {
                return json_decode($generalSettings->business, true)['currency'].' '.$row->total_ingredient_cost;
            })
            ->editColumn('production_cost', function ($row) use ($generalSettings)
            {
                return json_decode($generalSettings->business, true)['currency'].' '.$row->production_cost;
            })
            ->editColumn('total_cost', function ($row) use ($generalSettings)
            {
                return json_decode($generalSettings->business, true)['currency'].' '.$row->total_cost;
            })
            ->rawColumns(['action', 'product', 'wastage_percent', 'total_output_qty', 'total_ingredient_cost', 'production_cost', 'total_cost'])
            ->make(true);
        }

        return view('manufacturing.process.index', compact('products'));
    }

    // Process index view method
    public function create(Request $request)
    {
        $product = [];
        $productAndVariantId = explode('-', $request->product_id);
        $product_id = $productAndVariantId[0];
        $variant_id = $productAndVariantId[1];
        if ($variant_id != 'NULL') {
            $v_product = DB::table('product_variants')->where('id', $variant_id)
            ->leftJoin('products', 'product_variants.product_id', 'products.id')
            ->select(
                'product_variants.id as v_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'products.id as p_id',
                'products.name',
                'products.product_code',
            )->first();
            $product['p_id'] = $v_product->p_id;
            $product['p_name'] = $v_product->name;
            $product['p_code'] = $v_product->product_code;
            $product['v_id'] = $v_product->v_id;
            $product['v_name'] = $v_product->variant_name;
            $product['v_code'] = $v_product->variant_code;
        }else{
            $s_product = Product::where('id', $product_id)
            ->select('id', 'name', 'product_code')
            ->first();
            $product['p_id'] = $s_product->id;
            $product['p_name'] = $s_product->name;
            $product['p_code'] = $s_product->product_code;
            $product['v_id'] = NULL;
            $product['v_name'] = NULL;
            $product['v_code'] = NULL;
        }

        return view('manufacturing.process.create', compact('product'));
    }

    // Store process
    public function store(Request $request)
    {
        $this->validate($request, [
            'total_cost' => 'required',
        ]);

        $addProcess = new Process();
        $addProcess->product_id = $request->product_id;
        $addProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : NULL;
        $addProcess->total_ingredient_cost = $request->total_ingredient_cost;
        $addProcess->wastage_percent = $request->wastage_percent;
        $addProcess->total_output_qty = $request->total_output_qty;
        $addProcess->unit_id = $request->unit_id;
        $addProcess->production_cost = $request->production_cost ? $request->production_cost : 0;
        $addProcess->total_cost = $request->total_cost;
        $addProcess->save();

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $ingredient_wastage_percents = $request->ingredient_wastage_percents;
        $final_quantities = $request->final_quantities;
        $unit_ids = $request->unit_ids;
        $prices = $request->prices;

        if (count($request->product_ids) > 0) {
            $index = 0;
            foreach ($product_ids as $product_id) {
                $addProcessIngredient = new ProcessIngredient();
                $addProcessIngredient->process_id = $addProcess->id;
                $addProcessIngredient->product_id = $product_id;
                $addProcessIngredient->variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
                $addProcessIngredient->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addProcessIngredient->wastage_percent = $ingredient_wastage_percents[$index];
                $addProcessIngredient->final_qty = $final_quantities[$index];
                $addProcessIngredient->unit_id = $unit_ids[$index];
                $addProcessIngredient->subtotal = $prices[$index];
                $addProcessIngredient->save();
                $index++;
            }
        }

        return response()->json('Manufacturing Process created successfully');
    }

    public function edit($processId)
    {
        $process = DB::table('processes')->where('processes.id', $processId)
        ->leftJoin('products', 'processes.product_id', 'products.id')
        ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
        ->select(
            'processes.*',
            'products.id as p_id',
            'products.name as p_name',
            'products.product_code as p_code',
            'product_variants.id as v_id',
            'product_variants.variant_name as v_name',
            'product_variants.variant_code as v_code',
        )
        ->first();
         $units = DB::table('units')->select('id', 'name')->get();
        $processIngredients = DB::table('process_ingredients')->where('process_id', $processId)->get();
        return view('manufacturing.process.edit', compact('process', 'units', 'processIngredients'));
    }
}
