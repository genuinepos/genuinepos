<?php

namespace App\Services\Manufacturing;

use App\Enums\IsDeleteInUpdate;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Manufacturing\Process;
use Yajra\DataTables\Facades\DataTables;

class ProcessService
{
    public function processTable($request)
    {
        $generalSettings = config('generalSettings');

        $process = DB::table('processes')
            ->leftJoin('branches', 'processes.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->leftJoin('categories', 'products.category_id', 'categories.id')
            ->leftJoin('categories as subCate', 'products.sub_category_id', 'subCate.id')
            ->leftJoin('units', 'processes.unit_id', 'units.id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->where('product_access_branches.branch_id', auth()->user()->branch_id)
            ->select(
                'processes.*',
                'branches.name as branch_name',
                'branches.area_name as branch_area_name',
                'branches.branch_code',
                'parentBranch.name as parent_branch_name',
                'products.name as p_name',
                'product_variants.variant_name as v_name',
                'categories.name as cate_name',
                'subCate.name as sub_cate_name',
                'units.name as unit_name',
            )->orderBy('processes.id', 'desc');

        return DataTables::of($process)
            ->addColumn('action', function ($row) {
                $html = '';
                $html .= '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __("Action") . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a href="' . route('manufacturing.process.show', [$row->id]) . '" class="dropdown-item" id="details_btn"> ' . __("View") . '</a>';

                if (auth()->user()->can('process_edit')) {

                    $html .= '<a class="dropdown-item" href="' . route('manufacturing.process.edit', [$row->id]) . '"> ' . __("Edit") . '</a>';
                }

                if (auth()->user()->can('process_delete')) {

                    $html .= '<a class="dropdown-item" id="delete" href="' . route('manufacturing.process.delete', [$row->id]) . '">' . __("Delete") . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('branch', function ($row) use ($generalSettings) {

                if ($row->branch_id) {

                    if ($row->parent_branch_name) {

                        return $row->parent_branch_name . '(' . $row->area_name . ')';
                    } else {

                        return $row->branch_name . '(' . $row->area_name . ')';
                    }
                } else {

                    return $generalSettings['business__shop_name'] . '(Business)';
                }
            })
            ->editColumn('product', fn ($row) => $row->p_name . ' ' . $row->v_name)
            ->editColumn('total_output_qty', function ($row) {

                $wastage = $row->total_output_qty / 100 * $row->wastage_percent;
                $qtyWithWastage = $row->total_output_qty - $wastage;
                return \App\Utils\Converter::format_in_bdt($qtyWithWastage) . '/' . $row->unit_name;
            })
            ->editColumn('total_ingredient_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->total_ingredient_cost))
            ->editColumn('additional_production_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->additional_production_cost))
            ->editColumn('net_cost', fn ($row) => \App\Utils\Converter::format_in_bdt($row->net_cost))
            ->rawColumns(['action', 'product', 'total_output_qty', 'total_ingredient_cost', 'additional_production_cost', 'total_cost'])
            ->make(true);
    }

    public function addProcess(object $request): object
    {
        $addProcess = new Process();
        $addProcess->branch_id = auth()->user()->branch_id;
        $addProcess->product_id = $request->product_id;
        $addProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : null;
        $addProcess->total_ingredient_cost = $request->total_ingredient_cost;
        $addProcess->total_output_qty = $request->total_output_qty;
        $addProcess->unit_id = $request->unit_id;
        $addProcess->additional_production_cost = $request->additional_production_cost;
        $addProcess->net_cost = $request->net_cost;
        $addProcess->production_instruction = $request->production_instruction;
        $addProcess->save();

        return $addProcess;
    }

    public function updateProcess(object $request, int $id): object
    {
        $updateProcess = $this->process(with: ['ingredients'])->where('id', $id)->first();

        foreach ($updateProcess->ingredients as $ingredient) {

            $ingredient->is_delete_in_update = IsDeleteInUpdate::Yes->value;
            $ingredient->save();
        }

        $updateProcess->product_id = $request->product_id;
        $updateProcess->variant_id = $request->variant_id != 'noid' ? $request->variant_id : null;
        $updateProcess->total_ingredient_cost = $request->total_ingredient_cost;
        $updateProcess->total_output_qty = $request->total_output_qty;
        $updateProcess->unit_id = $request->unit_id;
        $updateProcess->additional_production_cost = $request->additional_production_cost ? $request->additional_production_cost : 0;
        $updateProcess->net_cost = $request->net_cost;
        $updateProcess->production_instruction = $request->production_instruction;
        $updateProcess->save();

        return $updateProcess;
    }

    function deleteProcess(int $id): void
    {
        $deleteProcess = $this->process()->where('id', $id)->first();
        if (!is_null($deleteProcess)) {

            $deleteProcess->delete();
        }
    }

    public function processes(array $with = null): ?object
    {
        return DB::table('processes')
            ->leftJoin('products', 'processes.product_id', 'products.id')
            ->leftJoin('product_variants', 'processes.variant_id', 'product_variants.id')
            ->leftJoin('product_access_branches', 'products.id', 'product_access_branches.product_id')
            ->where('product_access_branches.branch_id', auth()->user()->branch_id)
            ->select(
                'processes.*',
                'products.id as product_id',
                'products.name as product_name',
                'products.product_code as product_code',
                'products.tax_ac_id',
                'products.tax_type',
                'product_variants.id as variant_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
            )->get();
    }

    public function process(array $with = null): ?object
    {
        $query = Process::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function getProcessableProductForCreate(object $request): object
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
                    'products.id as product_id',
                    'products.product_code',
                    'units.id as unit_id',
                    'units.name as unit_name',
                )->first();

            $product['product_id'] = $variantProduct->product_id;
            $product['unit_id'] = $variantProduct->unit_id;
            $product['unit_name'] = $variantProduct->unit_name;
            $product['product_name'] = $variantProduct->name;
            $product['product_code'] = $variantProduct->product_code;
            $product['variant_id'] = $variantProduct->variant_id;
            $product['variant_name'] = $variantProduct->variant_name;
            $product['variant_code'] = $variantProduct->variant_code;
        } else {

            $product = Product::with('unit')->where('id', $productId)
                ->select('id', 'unit_id', 'name', 'product_code')
                ->first();

            $product['product_id'] = $product->id;
            $product['unit_id'] = $product?->unit?->id;
            $product['unit_name'] = $product?->unit?->name;
            $product['product_name'] = $product->name;
            $product['product_code'] = $product->product_code;
            $product['variant_id'] = null;
            $product['variant_name'] = null;
            $product['variant_code'] = null;
        }

        return $product;
    }
}
