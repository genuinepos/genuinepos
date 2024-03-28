<?php

namespace App\Services\Products;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Products\StockIssueProduct;

class StockIssueProductService
{
    public function stockIssuedProductsTable(object $request): object
    {
        $generalSettings = config('generalSettings');
        $stockIssueProducts = '';

        $query = DB::table('stock_issue_products')
            ->leftJoin('stock_issues', 'stock_issue_products.stock_issue_id', 'stock_issues.id')
            ->leftJoin('products', 'stock_issue_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'stock_issue_products.variant_id', 'product_variants.id')
            ->leftJoin('hrm_departments', 'stock_issues.department_id', 'hrm_departments.id')
            ->leftJoin('users as reported_by', 'stock_issues.reported_by_id', 'reported_by.id')
            ->leftJoin('branches', 'stock_issues.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id');

        $this->filter(request: $request, query: $query);

        $stockIssueProducts = $query->select(
            'stock_issue_products.quantity',
            'stock_issue_products.unit_cost_inc_tax',
            'stock_issue_products.subtotal',
            'stock_issues.id',
            'stock_issues.branch_id',
            'stock_issues.voucher_no',
            'stock_issues.date',
            'stock_issues.date_ts',
            'products.name as product_name',
            'product_variants.variant_name',
            'hrm_departments.name as department_name',
            'branches.name as branch_name',
            'branches.area_name as branch_area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'reported_by.prefix as reported_by_prefix',
            'reported_by.name as reported_by_name',
            'reported_by.last_name as reported_by_last_name'
        )->orderBy('stock_issues.date_ts', 'desc');

        return DataTables::of($stockIssueProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return $row->product_name . $variant;
            })
            ->editColumn('date', function ($row) use ($generalSettings) {

                $__date_format = $generalSettings['business_or_shop__date_format'];

                return date($__date_format, strtotime($row->date));
            })

            ->editColumn('voucher_no', function ($row) {

                return '<a href="' . route('stock.issues.show', [$row->id]) . '" id="detailsBtn">' . $row->voucher_no . '</a>';
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

            ->editColumn('quantity', fn ($row) => '<span class="quantity" data-value="' . $row->quantity . '">' . \App\Utils\Converter::format_in_bdt($row->quantity) . '</span>')

            ->editColumn('unit_cost_inc_tax', fn ($row) => '<span class="unit_cost_inc_tax" data-value="' . $row->unit_cost_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->unit_cost_inc_tax) . '</span>')

            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

            ->editColumn('reported_by', function ($row) {

                return $row->reported_by_prefix . ' ' . $row->reported_by_name . ' ' . $row->reported_by_last_name;
            })

            ->rawColumns(['product', 'date', 'quantity', 'unit_cost_inc_tax', 'subtotal', 'voucher_no', 'branch', 'reported_by'])
            ->make(true);
    }

    public function addStockIssueProduct(object $request, object $stockIssue, int $index): object
    {
        $variantId = $request->variant_ids[$index] == 'noid' ? null : $request->variant_ids[$index];
        $warehouseId = isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null;
        $addStockIssueProduct = new StockIssueProduct();
        $addStockIssueProduct->stock_issue_id = $stockIssue->id;
        $addStockIssueProduct->branch_id = $stockIssue->branch_id;
        $addStockIssueProduct->warehouse_id = $warehouseId;
        $addStockIssueProduct->product_id = $request->product_ids[$index];
        $addStockIssueProduct->variant_id = $variantId;
        $addStockIssueProduct->quantity = $request->quantities[$index];
        $addStockIssueProduct->unit_id = $request->unit_ids[$index];
        $addStockIssueProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addStockIssueProduct->subtotal = $request->subtotals[$index];
        $addStockIssueProduct->save();

        return $addStockIssueProduct;
    }

    public function updateStockIssueProduct(object $request, object $stockIssue, int $index): object
    {
        $addOrEditStockIssueProduct = null;
        $currentWarehouseId = null;
        $stockIssueProduct = $this->singleStockIssueProduct(id: $request->stock_issue_product_id);
        if (isset($stockIssueProduct)) {

            $addOrEditStockIssueProduct = $stockIssueProduct;
            $currentWarehouseId = $stockIssueProduct->warehouse_id;
        } else {

            $addOrEditStockIssueProduct = new StockIssueProduct();
        }

        $variantId = $request->variant_ids[$index] == 'noid' ? null : $request->variant_ids[$index];
        $warehouseId = isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null;

        $addOrEditStockIssueProduct->stock_issue_id = $stockIssue->id;
        $addOrEditStockIssueProduct->branch_id = $stockIssue->branch_id;
        $addOrEditStockIssueProduct->warehouse_id = $warehouseId;
        $addOrEditStockIssueProduct->product_id = $request->product_ids[$index];
        $addOrEditStockIssueProduct->variant_id = $variantId;
        $addOrEditStockIssueProduct->quantity = $request->quantities[$index];
        $addOrEditStockIssueProduct->unit_id = $request->unit_ids[$index];
        $addOrEditStockIssueProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrEditStockIssueProduct->subtotal = $request->subtotals[$index];
        $addOrEditStockIssueProduct->is_delete_in_update = BooleanType::False->value;
        $addOrEditStockIssueProduct->save();
        $addOrEditStockIssueProduct->current_warehouse_id = $currentWarehouseId;
        return $addOrEditStockIssueProduct;
    }

    public function singleStockIssueProduct(?int $id, ?array $with = null): ?object
    {
        $query = StockIssueProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function stockIssueProducts(array $with): ?object
    {
        $query = StockIssueProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    private function filter(object $request, object $query)
    {
        if ($request->product_id) {

            $query->where('stock_issue_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('stock_issue_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('stock_issues.branch_id', null);
            } else {

                $query->where('stock_issues.branch_id', $request->branch_id);
            }
        }

        if ($request->department_id) {

            $query->where('stock_issues.department_id', $request->department_id);
        }

        // if ($request->reported_by_id) {

        //     $query->where('stock_issues.reported_by_id', $request->reported_by_id);
        // }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('stock_issues.date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
            $query->where('stock_issues.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }
}
