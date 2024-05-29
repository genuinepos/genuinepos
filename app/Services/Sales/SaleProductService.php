<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\RoleType;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use Illuminate\Support\Str;
use App\Enums\SaleScreenType;
use App\Models\Sales\SaleProduct;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleProductService
{
    public function soldProductListTable($request)
    {
        $generalSettings = config('generalSettings');
        $saleProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->leftJoin('branches', 'sales.branch_id', 'branches.id')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('accounts as customers', 'sales.customer_account_id', 'customers.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->leftJoin('warehouses', 'sale_products.warehouse_id', 'warehouses.id')
            ->where('sales.status', SaleStatus::Final->value);

        if ($request->product_id) {

            $query->where('sale_products.product_id', $request->product_id);
        }

        if ($request->variant_id) {

            $query->where('sale_products.variant_id', $request->variant_id);
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('sales.branch_id', null);
            } else {

                $query->where('sales.branch_id', $request->branch_id);
            }
        }

        if ($request->customer_account_id) {

            $query->where('sales.customer_account_id', $request->customer_account_id);
        }

        if ($request->sale_screen) {

            $query->where('sales.sale_screen', $request->sale_screen);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sales.sale_date_ts', $date_range); // Final
        }

        // if (auth()->user()->role_type == RoleType::Other->value || auth()->user()->is_belonging_an_area == BooleanType::True->value) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('sales.branch_id', auth()->user()->branch_id);
        }

        $saleProducts = $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.variant_id',
            'sale_products.quantity',
            'sale_products.unit_discount_amount',
            'sale_products.unit_tax_percent',
            'sale_products.unit_tax_amount',
            'sale_products.unit_cost_inc_tax',
            'sale_products.unit_price_exc_tax',
            'sale_products.unit_price_inc_tax',
            'sale_products.subtotal',
            'units.code_name as unit_code',
            'sales.id',
            'sales.branch_id',
            'sales.customer_account_id',
            'sales.date',
            'sales.invoice_id',
            'sales.sale_screen',
            'products.name',
            'products.product_code',
            'products.product_price',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_price',
            'customers.name as customer_name',
            'branches.name as branch_name',
            'branches.area_name',
            'branches.branch_code',
            'parentBranch.name as parent_branch_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
        )->orderBy('sales.sale_date_ts', 'desc');

        return DataTables::of($saleProducts)
            ->editColumn('product', function ($row) {

                $variant = $row->variant_name ? ' - ' . $row->variant_name : '';
                return Str::limit($row->name, 35, '') . $variant;
            })
            ->editColumn('date', function ($row) {

                return date('d/m/Y', strtotime($row->date));
            })
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

            ->editColumn('invoice_id', fn ($row) => '<a href="' . route('sales.show', [$row->sale_id]) . '" class="text-hover" id="details_btn" title="View">' . $row->invoice_id . '</a>')

            ->editColumn('sale_screen', fn ($row) => '<span class="text-info fw-bold">' . SaleScreenType::tryFrom($row->sale_screen)->name . '</span>')

            ->editColumn('quantity', fn ($row) => '<span class="quantity" data-value="' . $row->quantity . '">' . \App\Utils\Converter::format_in_bdt($row->quantity) . '/' . $row->unit_code . '</span>')

            ->editColumn('unit_price_exc_tax', fn ($row) => '<span class="unit_price_exc_tax" data-value="' . $row->unit_price_exc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->unit_price_exc_tax) . '</span>')

            ->editColumn('unit_discount_amount', fn ($row) => '<span class="unit_discount_amount" data-value="' . $row->unit_discount_amount . '">' . \App\Utils\Converter::format_in_bdt($row->unit_discount_amount) . '</span>')

            ->editColumn('unit_tax_amount', fn ($row) => '<span class="unit_tax_amount" data-value="' . $row->unit_tax_amount . '">' . '(' . $row->unit_tax_percent . '%)=' . \App\Utils\Converter::format_in_bdt($row->unit_tax_amount) . '</span>')

            ->editColumn('unit_price_inc_tax', fn ($row) => '<span class="unit_price_inc_tax" data-value="' . $row->unit_price_inc_tax . '">' . \App\Utils\Converter::format_in_bdt($row->unit_price_inc_tax) . '</span>')

            ->editColumn('subtotal', fn ($row) => '<span class="subtotal" data-value="' . $row->subtotal . '">' . \App\Utils\Converter::format_in_bdt($row->subtotal) . '</span>')

            ->rawColumns(['product', 'product_code', 'date', 'invoice_id', 'branch', 'stock_location', 'sale_screen', 'quantity', 'unit_price_exc_tax', 'unit_discount_amount', 'unit_tax_amount', 'unit_price_inc_tax', 'subtotal'])
            ->make(true);
    }

    public function addSaleProduct(object $request, object $sale, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouseId = isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null;
        $addSaleProduct = new SaleProduct();
        $addSaleProduct->sale_id = $sale->id;
        $addSaleProduct->warehouse_id = $warehouseId;
        $addSaleProduct->branch_id = $sale->branch_id;
        $addSaleProduct->product_id = $request->product_ids[$index];
        $addSaleProduct->variant_id = $variantId;
        $addSaleProduct->quantity = $request->quantities[$index];
        $addSaleProduct->ordered_quantity = $sale->status == SaleStatus::Order->value ? $request->quantities[$index] : 0;
        $addSaleProduct->left_quantity = $sale->status == SaleStatus::Order->value ? $request->quantities[$index] : 0;
        $addSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addSaleProduct->tax_type = $request->tax_types[$index];
        $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addSaleProduct->unit_id = $request->unit_ids[$index];
        $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addSaleProduct->subtotal = $request->subtotals[$index];
        $addSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addSaleProduct->save();

        return $addSaleProduct;
    }

    public function updateSaleProduct(object $request, object $sale, int $index): object
    {
        $variantId = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $warehouseId = isset($request->warehouse_ids[$index]) ? $request->warehouse_ids[$index] : null;

        $saleProduct = $this->singleSaleProduct(id: $request->sale_product_ids[$index]);

        $currentTaxAcId = $saleProduct ? $saleProduct->tax_ac_id : null;
        $currentWarehouseId = $saleProduct ? $saleProduct->warehouse_id : null;

        $addOrUpdateSaleProduct = '';
        if ($saleProduct) {

            $addOrUpdateSaleProduct = $saleProduct;
        } else {

            $addOrUpdateSaleProduct = new SaleProduct();
        }

        $addOrUpdateSaleProduct->sale_id = $sale->id;
        $addOrUpdateSaleProduct->warehouse_id = $warehouseId;
        $addOrUpdateSaleProduct->branch_id = $sale->branch_id;
        $addOrUpdateSaleProduct->product_id = $request->product_ids[$index];
        $addOrUpdateSaleProduct->variant_id = $variantId;
        $addOrUpdateSaleProduct->quantity = $request->quantities[$index];
        $addOrUpdateSaleProduct->unit_discount_type = $request->unit_discount_types[$index];
        $addOrUpdateSaleProduct->unit_discount = $request->unit_discounts[$index];
        $addOrUpdateSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$index];
        $addOrUpdateSaleProduct->tax_ac_id = $request->tax_ac_ids[$index];
        $addOrUpdateSaleProduct->tax_type = $request->tax_types[$index];
        $addOrUpdateSaleProduct->unit_tax_percent = $request->unit_tax_percents[$index];
        $addOrUpdateSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$index];
        $addOrUpdateSaleProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$index];
        $addOrUpdateSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$index];
        $addOrUpdateSaleProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateSaleProduct->description = $request->descriptions[$index] ? $request->descriptions[$index] : null;
        $addOrUpdateSaleProduct->is_delete_in_update = 0;
        $addOrUpdateSaleProduct->save();

        $addOrUpdateSaleProduct->current_tax_ac_id = $currentTaxAcId;
        $addOrUpdateSaleProduct->current_warehouse_id = $currentWarehouseId;

        return $addOrUpdateSaleProduct;
    }

    public function customerCopySaleProducts($saleId)
    {
        return DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('warranties', 'products.warranty_id', 'warranties.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->leftJoin('product_units', 'sale_products.product_unit_id', 'product_units.id')
            ->leftJoin('units as assignedUnit', 'product_units.assigned_unit_id', 'assignedUnit.id')
            ->select(
                'sale_products.product_id',
                'sale_products.variant_id',
                'sale_products.description',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_amount',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                // 'sale_products.subtotal',
                // 'sale_products.ex_status',
                'products.name as p_name',
                'products.product_code',
                'products.warranty_id',
                'product_variants.variant_name',
                'product_variants.variant_code',
                'warranties.duration as w_duration',
                'warranties.duration_type as w_duration_type',
                'warranties.description as w_description',
                'warranties.type as w_type',
                'units.code_name as unit_code_name',
                'assignedUnit.code_name as assigned_unit_code_name',
                DB::raw('SUM(sale_products.quantity) as quantity'),
                DB::raw('SUM(sale_products.subtotal) as subtotal'),
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.unit_tax_amount')
            // ->groupBy('sale_products.subtotal')
            // ->groupBy('sale_products.ex_status')
            ->groupBy('products.warranty_id')
            ->groupBy('products.name')
            ->groupBy('products.product_code')
            ->groupBy('warranties.duration')
            ->groupBy('warranties.duration_type')
            ->groupBy('warranties.type')
            ->groupBy('warranties.description')
            ->groupBy('product_variants.variant_name')
            ->groupBy('product_variants.variant_code')
            ->groupBy('units.code_name')
            ->groupBy('assignedUnit.code_name')
            ->get();
    }

    public function exchangeableSaleProducts(?int $saleId)
    {
        return DB::table('sale_products')
            ->where('sale_products.sale_id', $saleId)
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.variant_id', 'product_variants.id')
            ->leftJoin('units', 'sale_products.unit_id', 'units.id')
            ->select(
                'sale_products.product_id',
                'sale_products.variant_id',
                'sale_products.description',
                'sale_products.unit_price_exc_tax',
                'sale_products.unit_price_inc_tax',
                'sale_products.unit_discount_type',
                'sale_products.unit_discount',
                'sale_products.unit_discount_amount',
                'sale_products.tax_ac_id',
                'sale_products.tax_type',
                'sale_products.unit_tax_percent',
                'sale_products.unit_tax_amount',
                'sale_products.unit_id',
                'products.name as product_name',
                'product_variants.variant_name',
                'units.name as unit_name',
                DB::raw('SUM(sale_products.quantity) as quantity'),
                DB::raw('SUM(sale_products.subtotal) as subtotal'),
            )
            ->groupBy('sale_products.product_id')
            ->groupBy('sale_products.variant_id')
            ->groupBy('sale_products.description')
            ->groupBy('sale_products.unit_price_exc_tax')
            ->groupBy('sale_products.unit_price_inc_tax')
            ->groupBy('sale_products.unit_discount_type')
            ->groupBy('sale_products.unit_discount')
            ->groupBy('sale_products.unit_discount_amount')
            ->groupBy('sale_products.tax_ac_id')
            ->groupBy('sale_products.tax_type')
            ->groupBy('sale_products.unit_tax_percent')
            ->groupBy('sale_products.unit_tax_amount')
            ->groupBy('sale_products.unit_id')
            ->groupBy('products.name')
            ->groupBy('product_variants.variant_name')
            ->groupBy('units.name')
            ->orderBy('sale_products.product_id')
            ->get();
    }

    public function saleProducts(array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singleSaleProduct(?int $id, array $with = null): ?object
    {
        $query = SaleProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
