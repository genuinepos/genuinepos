<?php

namespace App\Services\Products;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Products\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Products\ProductStock;
use App\Models\Products\ProductVariant;

class ProductStockService
{
    public function adjustMainProductAndVariantStock(int $productId, int $variantId = null): void
    {
        $product = Product::where('id', $productId)->first();

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')->where('product_ledgers.product_id', $productId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out')
                )->groupBy('product_ledgers.product_id')->get();

            $productCurrentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');
            $product->quantity = $productCurrentStock;
            $product->save();

            if ($variantId) {

                $productLedger = DB::table('product_ledgers')->where('product_ledgers.product_id')
                    ->where('product_ledgers.variant_id', $variantId)
                    ->select(
                        DB::raw('SUM(product_ledgers.in) as stock_in'),
                        DB::raw('SUM(product_ledgers.out) as stock_out')
                    )->groupBy('product_ledgers.variant_id')->get();

                $variantCurrentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');
                $variant = ProductVariant::where('id', $variantId)->first();
                $variant->variant_quantity = $variantCurrentStock;
                $variant->save();
            }
        }
    }

    public function adjustBranchStock(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addBranchProduct(productId: $productId, variantId: $variantId, branchId: $branchId);

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.branch_id', $branchId)
                ->where('product_ledgers.warehouse_id', null)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out'),
                    DB::raw('SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_purchased_cost'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $currentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');

            $avgUnitCost = $currentStock > 0 ? $productLedger->sum('total_purchased_cost') / $currentStock : $product->product_cost;
            $stockValue = $avgUnitCost * $currentStock;

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)
                ->first();

            $productStock->stock = $currentStock;
            $productStock->stock_value = $stockValue;
            $productStock->save();
        }
    }

    public function adjustBranchAllStock(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addBranchProduct(productId: $productId, variantId: $variantId, branchId: $branchId);

        if ($product->is_manage_stock == 1) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.branch_id', $branchId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as all_stock_in'),
                    DB::raw('SUM(product_ledgers.out) as all_stock_out'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $allStock = $productLedger->sum('all_stock_in') - $productLedger->sum('all_stock_out');

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)
                ->first();

            $productStock->all_stock = $allStock;
            $productStock->save();
        }
    }

    public function adjustWarehouseStock(int $productId, int $variantId = null, int $warehouseId): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock', 'product_cost')
            ->first();

        $this->addWarehouseProduct(productId: $productId, variantId: $variantId, warehouseId: $warehouseId);

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productLedger = DB::table('product_ledgers')
                ->where('product_ledgers.product_id', $productId)
                ->where('product_ledgers.variant_id', $variantId)
                ->where('product_ledgers.warehouse_id', $warehouseId)
                ->select(
                    DB::raw('SUM(product_ledgers.in) as stock_in'),
                    DB::raw('SUM(product_ledgers.out) as stock_out'),
                    DB::raw('SUM(case when product_ledgers.in != 0 then product_ledgers.subtotal end) as total_purchased_cost'),
                )->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

            $currentStock = $productLedger->sum('stock_in') - $productLedger->sum('stock_out');

            $avgUnitCost = $currentStock > 0 ? $productLedger->sum('total_purchased_cost') / $currentStock : $product->product_cost;
            $stockValue = $avgUnitCost * $currentStock;

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('warehouse_id', $warehouseId)->first();

            $productStock->stock = $currentStock;
            $productStock->stock_value = $stockValue;
            $productStock->save();
        }
    }

    public function addWarehouseProduct(int $productId, int $variantId = null, int $warehouseId): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == 1) {

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('warehouse_id', $warehouseId)->first();

            if (! $productStock) {

                $addProductStock = new ProductStock();
                $addProductStock->product_id = $productId;
                $addProductStock->variant_id = $variantId;
                $addProductStock->warehouse_id = $warehouseId;
                $addProductStock->branch_id = auth()->user()->branch_id;
                $addProductStock->save();
            }
        }
    }

    public function addBranchProduct(int $productId, int $variantId = null, int $branchId = null): void
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        if ($product->is_manage_stock == BooleanType::True->value) {

            $productStock = ProductStock::where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->where('branch_id', $branchId)
                ->where('warehouse_id', null)->first();

            if (! $productStock) {

                $addProductStock = new ProductStock();
                $addProductStock->product_id = $productId;
                $addProductStock->variant_id = $variantId;
                $addProductStock->branch_id = $branchId;
                $addProductStock->save();
            }
        }
    }

    public function getAllStockUnderTheBranch(int $productId, int $variantId = null, int $branchId = null): float
    {
        $product = DB::table('products')
            ->where('id', $productId)->select('id', 'is_manage_stock')
            ->first();

        $productStock = ProductStock::where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('branch_id', $branchId)
            ->where('warehouse_id', null)->first();
    }

    public function productStock(?int $id, object $request): array
    {
        $converter = new \App\Utils\Converter();
        $amounts = '';
        $query = DB::table('product_ledgers')->where('product_ledgers.product_id', $id);

        $accountStartDateYmd = '';
        $fromDateYmd = '';
        $toDateYmd = '';
        if ($request->from_date && $request->to_date) {

            $generalSettings = config('generalSettings');
            $accountStartDate = $generalSettings['business_or_shop__account_start_date'];

            $fromDateYmd = Carbon::parse($request->from_date)->startOfDay();
            $toDateYmd = Carbon::parse($request->to_date)->endOfDay();
            $accountStartDateYmd = Carbon::parse($accountStartDate)->startOfDay();
        }

        if ($request->branch_id) {

            if ($request->branch_id == 'NULL') {

                $query->where('product_ledgers.branch_id', null);
            } else {

                $query->where('product_ledgers.branch_id', $request->branch_id);
            }
        }

        if ($request->variant_id) {

            $query->where('product_ledgers.variant_id', $request->variant_id);
        }

        // if (auth()->user()->role_type == 3 || auth()->user()->is_belonging_an_area == 1) {
        if (!auth()->user()->can('has_access_to_all_area') || auth()->user()->is_belonging_an_area == BooleanType::True->value) {

            $query->where('product_ledgers.branch_id', auth()->user()->branch_id);
        }

        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) < '$fromDateYmd' then product_ledgers.in end), 0) as opening_total_in"),
                DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) < '$fromDateYmd' then product_ledgers.out end), 0) as opening_total_out"),
                DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.in end), 0) as curr_total_in"),
                DB::raw("IFNULL(SUM(case when timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.out end), 0) as curr_total_out"),
            );
        } elseif ($fromDateYmd && $toDateYmd && $fromDateYmd <= $accountStartDateYmd) {

            $query->select(
                DB::raw("IFNULL(SUM(case when product_ledgers.voucher_type = 0 and timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.in end), 0) as opening_total_in"),
                DB::raw("IFNULL(SUM(case when product_ledgers.voucher_type = 0 and timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.out end), 0) as opening_total_out"),
                DB::raw("IFNULL(SUM(case when product_ledgers.voucher_type != 0 and timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.in end), 0) as curr_total_in"),
                DB::raw("IFNULL(SUM(case when product_ledgers.voucher_type != 0 and timestamp(product_ledgers.date_ts) > '$fromDateYmd' and timestamp(product_ledgers.date_ts) < '$toDateYmd' then product_ledgers.out end), 0) as curr_total_out"),
            );
        } else {

            $query->select(
                DB::raw('IFNULL(SUM(case when product_ledgers.voucher_type = 0 then product_ledgers.in end), 0) as opening_total_in'),
                DB::raw('IFNULL(SUM(case when product_ledgers.voucher_type = 0 then product_ledgers.out end), 0) as opening_total_out'),
                DB::raw('IFNULL(SUM(case when product_ledgers.voucher_type != 0 then product_ledgers.in end), 0) as curr_total_in'),
                DB::raw('IFNULL(SUM(case when product_ledgers.voucher_type != 0 then product_ledgers.out end), 0) as curr_total_out'),
            );
        }

        $amounts = $query->groupBy('product_ledgers.product_id', 'product_ledgers.variant_id')->get();

        $openingStockIn = $amounts->sum('opening_total_in');
        $__openingStockIn = $amounts->sum('opening_total_in');
        $openingStockOut = $amounts->sum('opening_total_out');
        $__openingStockOut = $amounts->sum('opening_total_out');

        $currTotalIn = $amounts->sum('curr_total_in');
        $__currTotalIn = $amounts->sum('curr_total_in');
        $currTotalOut = $amounts->sum('curr_total_out');
        $__currTotalOut = $amounts->sum('curr_total_out');

        $currOpeningStock = $openingStockIn - $openingStockOut;

        $currTotalIn += $currOpeningStock >= 0 ? $currOpeningStock : 0;
        $currTotalOut += $currOpeningStock < 0 ? abs($currOpeningStock) : 0;

        $closingStock = $currTotalIn - $currTotalOut;

        $allTotalIn = 0;
        $allTotalOut = 0;
        if ($fromDateYmd && $toDateYmd && $fromDateYmd > $accountStartDateYmd) {

            $allTotalIn = $__currTotalIn + ($currOpeningStock >= 0 ? $currOpeningStock : 0);
            $allTotalOut = $__currTotalOut + ($currOpeningStock < 0 ? abs($currOpeningStock) : 0);
        } else {

            $allTotalIn = $__currTotalIn + $__openingStockIn;
            $allTotalOut = $__currTotalOut + $__openingStockOut;
        }

        return [
            'opening_stock' => $currOpeningStock ? $currOpeningStock : $converter::format_in_bdt(0),
            'opening_stock_string' => $currOpeningStock ? $converter::format_in_bdt($currOpeningStock < 0 ? abs($currOpeningStock) : $currOpeningStock) : $converter::format_in_bdt(0),
            'curr_total_in' => $__currTotalIn ? $__currTotalIn : $converter::format_in_bdt(0),
            'curr_total_in_string' => $__currTotalIn ? $converter::format_in_bdt($__currTotalIn) : $converter::format_in_bdt(0),
            'curr_total_out' => $__currTotalOut ? $__currTotalOut : $converter::format_in_bdt(0),
            'curr_total_out_string' => $__currTotalOut ? $converter::format_in_bdt($__currTotalOut) : $converter::format_in_bdt(0),
            'all_total_in' => $allTotalIn ? $allTotalIn : $converter::format_in_bdt(0),
            'all_total_in_string' => $allTotalIn ? $converter::format_in_bdt($allTotalIn) : $converter::format_in_bdt(0),
            'all_total_out' => $allTotalOut ? $allTotalOut : $converter::format_in_bdt(0),
            'all_total_out_string' => $allTotalOut ? $converter::format_in_bdt($allTotalOut) : $converter::format_in_bdt(0),
            'closing_stock' => $closingStock,
            'closing_stock_string' => $converter::format_in_bdt($closingStock < 0 ? abs($closingStock) : $closingStock),
        ];
    }
}
