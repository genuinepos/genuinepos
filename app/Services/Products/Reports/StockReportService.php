<?php

namespace App\Services\Products\Reports;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StockReportService
{
    public function branchStockTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $branchStocks = $this->branchStockQuery(request: $request);

        return DataTables::of($branchStocks)
            ->editColumn('product_name', fn ($row) => Str::limit($row->product_name, 25, '') . ($row->variant_name ? ' - ' . $row->variant_name : ''))
            ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
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
            ->editColumn('cost', function ($row) {

                if ($row->variant_cost_with_tax) {

                    return \App\Utils\Converter::format_in_bdt($row->variant_cost_with_tax);
                } else {

                    return \App\Utils\Converter::format_in_bdt($row->product_cost_with_tax);
                }
            })
            ->editColumn('price', function ($row) {

                if ($row->variant_price) {

                    return \App\Utils\Converter::format_in_bdt($row->variant_price);
                } else {

                    return \App\Utils\Converter::format_in_bdt($row->product_price);
                }
            })
            ->editColumn('stock', fn ($row) => '<span class="branch_stock" data-value="' . $row->stock . '">' . $row->stock . '/' . $row->unit_code_name . '</span>')
            ->editColumn('stock_value', function ($row) {

                return '<span class="branch_stock_value" data-value="' . $row->stock_value . '">' . \App\Utils\Converter::format_in_bdt($row->stock_value) . '</span>';
            })
            ->rawColumns(['product_name', 'product_code', 'branch', 'cost', 'price', 'stock', 'stock_value'])
            ->make(true);
    }

    public function warehouseStockTable(object $request): object
    {
        $generalSettings = config('generalSettings');

        $warehouseStocks = $this->warehouseStockQuery(request: $request);

        return DataTables::of($warehouseStocks)
            ->editColumn('product_name', fn ($row) => Str::limit($row->product_name, 25, '') . ($row->variant_name ? ' - ' . $row->variant_name : ''))
            ->editColumn('product_code', fn ($row) => $row->variant_code ? $row->variant_code : $row->product_code)
            ->editColumn('stock_location', function ($row) use ($generalSettings) {

                $html = '';
                if ($row->is_global == BooleanType::True->value) {

                    $html .= '<p class="p-0 m-0">' . $row->warehouse_name . '/' . $row->warehouse_code . '-(<b>' . __('Global Warehouse') . '</b>)' . '</p>';
                } else {
                    if ($row->branch_id) {

                        if ($row->parent_branch_name) {

                            $html .= '<p class="p-0 m-0">(' . $row->parent_branch_name . '/' . $row->area_name . ')-<b>' . $row->warehouse_name . '/' . $row->warehouse_code . '</b></p>';
                        } else {

                            $html .= '<p class="p-0 m-0">(' . $row->branch_name . '/' . $row->area_name . ')-<b>' . $row->warehouse_name . '/' . $row->warehouse_code . '</b></p>';
                        }
                    } else {

                        $html .= '<p class="p-0 m-0">(' . $generalSettings['business_or_shop__business_name'] . ')-<b>' . $row->warehouse_name . '/' . $row->warehouse_code . '</b></></p>';
                    }
                }

                return $html;
            })
            ->editColumn('cost', function ($row) {

                if ($row->variant_cost_with_tax) {

                    return \App\Utils\Converter::format_in_bdt($row->variant_cost_with_tax);
                } else {

                    return \App\Utils\Converter::format_in_bdt($row->product_cost_with_tax);
                }
            })
            ->editColumn('price', function ($row) {

                if ($row->variant_price) {

                    return \App\Utils\Converter::format_in_bdt($row->variant_price);
                } else {

                    return \App\Utils\Converter::format_in_bdt($row->product_price);
                }
            })
            ->editColumn('stock', fn ($row) => '<span class="warehouse_stock" data-value="' . $row->stock . '">' . $row->stock . '/' . $row->unit_code_name . '</span>')
            ->editColumn('stock_value', function ($row) {

                return '<span class="warehouse_stock_value" data-value="' . $row->stock_value . '">' . \App\Utils\Converter::format_in_bdt($row->stock_value) . '</span>';
            })
            ->rawColumns(['product_name', 'product_code', 'stock_location', 'cost', 'price', 'stock', 'stock_value'])
            ->make(true);
    }

    public function branchStockQuery(object $request): object
    {
        $branchStocks = '';
        $query = DB::table('product_stocks')
            ->leftJoin('products', 'product_stocks.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_stocks.variant_id', 'product_variants.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('branches', 'product_stocks.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->where('product_stocks.warehouse_id', null);

        $this->filteredBranchStockQuery(request: $request, query: $query);

        return $query->select(
            'products.name as product_name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'units.code_name as unit_code_name',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'product_stocks.branch_id',
            'product_stocks.stock',
            'product_stocks.stock_value',
        )->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')
            ->orderBy('products.name', 'asc');
    }

    public function warehouseStockQuery(object $request): object
    {
        $query = DB::table('product_stocks')
            ->leftJoin('products', 'product_stocks.product_id', 'products.id')
            ->leftJoin('product_variants', 'product_stocks.variant_id', 'product_variants.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('warehouses', 'product_stocks.warehouse_id', 'warehouses.id')
            ->leftJoin('branches', 'warehouses.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->whereNotNull('product_stocks.warehouse_id');

        $this->filteredWarehouseStockQuery(request: $request, query: $query);

        return $query->select(
            'products.name as product_name',
            'products.product_code',
            'products.product_cost_with_tax',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'units.code_name as unit_code_name',
            'warehouses.is_global',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'branches.name as branch_name',
            'branches.branch_code',
            'branches.area_name',
            'parentBranch.name as parent_branch_name',
            'product_stocks.branch_id',
            'product_stocks.stock',
            'product_stocks.stock_value',
        )->orderBy('products.name', 'asc');
    }

    private function filteredBranchStockQuery(object $request, object $query)
    {
        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_stocks.branch_id', null);
            } else {

                $query->where('product_stocks.branch_id', $request->branch_id);
            }
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_id) {

            $query->where('products.tax_id', $request->tax_id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('product_stocks.branch_id', auth()->user()->branch_id);
        }

        return $query;
    }

    private function filteredWarehouseStockQuery(object $request, object $query)
    {
        if ($request->warehouse_id) {

            $query->where('product_stocks.warehouse_id', $request->warehouse_id);
        }

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->unit_id) {

            $query->where('products.unit_id', $request->unit_id);
        }

        if ($request->tax_id) {

            $query->where('products.tax_id', $request->tax_id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('warehouses.branch_id', auth()->user()->branch_id);

            if (empty($request->warehouse_id)) {

                $query->orWhere('warehouses.is_global', BooleanType::True->value);
            }
        }

        return $query;
    }
}
