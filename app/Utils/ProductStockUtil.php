<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductBranchVariant;
use App\Models\ProductWarehouseVariant;

class ProductStockUtil
{
    public function adjustMainProductAndVariantStock($product_id, $variant_id)
    {
        $productOpeningStock = DB::table('product_opening_stocks')
            ->where('product_id', $product_id)
            ->select(DB::raw('sum(quantity) as po_stock'))
            ->groupBy('product_id')->get();

        $productPurchase = DB::table('purchase_products')
            ->where('purchase_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_purchase'))
            ->groupBy('purchase_products.product_id')->get();

        $productSale = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sale_products.product_id', $product_id)
            ->where('sales.status', 1)
            ->select(DB::raw('sum(quantity) as total_sale'))
            ->groupBy('sale_products.product_id')->get();

        $totalPurchaseReturn = DB::table('purchase_return_products')
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('product_id')->get();

        $totalSaleReturn = DB::table('sale_return_products')
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('product_id')->get();

        $adjustment = DB::table('stock_adjustment_products')
            ->where('stock_adjustment_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_qty'))
            ->groupBy('stock_adjustment_products.product_id')->get();

        $productCurrentStock = $productPurchase->sum('total_purchase')
            + $productOpeningStock->sum('po_stock')
            + $totalSaleReturn->sum('total_return')
            - $productSale->sum('total_sale')
            - $adjustment->sum('total_qty');
            - $totalPurchaseReturn->sum('total_return');

        $product = Product::where('id', $product_id)->first();
        $product->quantity = $productCurrentStock;
        $product->number_of_sale = $productSale->sum('total_sale');
        $product->total_adjusted = $adjustment->sum('total_qty');
        $product->save();

        if ($variant_id) {
            $variantOpeningStock = DB::table('product_opening_stocks')
                ->where('product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as vo_stock'))
                ->groupBy('product_variant_id')->get();

            $variantPurchase = DB::table('purchase_products')
                ->where('purchase_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_variant_id')
                ->get();

            $variantSale = DB::table('sale_products')
                ->where('sale_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_variant_id')->get();

            $totalPurchaseReturn = DB::table('purchase_return_products')
                ->where('product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_variant_id')->get();

            $totalSaleReturn = DB::table('sale_return_products')
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('product_variant_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->where('stock_adjustment_products.product_id', $product_id)
                ->where('stock_adjustment_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_variant_id')->get();

            $variantCurrentStock = $variantPurchase->sum('total_purchase')
                + $variantOpeningStock->sum('vo_stock')
                + $totalSaleReturn->sum('total_return')
                - $variantSale->sum('total_sale')
                - $adjustment->sum('total_qty')
                - $totalPurchaseReturn->sum('total_return');

            $variant = ProductVariant::where('id', $variant_id)->first();
            $variant->variant_quantity = $variantCurrentStock;
            $variant->number_of_sale = $variantSale->sum('total_sale');
            $variant->total_adjusted = $adjustment->sum('total_qty');
            $variant->save();
        }
    }

    public function adjustMainBranchStock($product_id, $variant_id)
    {
        $productOpeningStock = DB::table('product_opening_stocks')
        ->where('product_opening_stocks.branch_id', NULL)
        ->where('product_id', $product_id)
        ->select(DB::raw('sum(quantity) as po_stock'))
        ->groupBy('product_opening_stocks.product_id')->get();

        $productSale = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.branch_id', NULL)
            ->where('sale_products.product_id', $product_id)
            ->where('sales.status', 1)
            ->select(DB::raw('sum(quantity) as total_sale'))
            ->groupBy('sale_products.product_id')->get();

        $productPurchase = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->where('purchases.branch_id', NULL)
            ->where('purchase_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_purchase'))
            ->groupBy('purchase_products.product_id')->get();

        $saleReturn = DB::table('sale_return_products')
            ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->where('sale_returns.branch_id', NULL)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('sale_return_products.product_id')->get();

        $purchaseReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->where('purchases.branch_id', NULL)
            ->where('purchases.warehouse_id', NULL)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $supplierReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->where('purchase_returns.purchase_id', NULL)
            ->where('purchase_returns.branch_id', NULL)
            ->where('purchase_returns.warehouse_id', NULL)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $transferred = DB::table('transfer_stock_to_warehouse_products')
        ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
        ->where('transfer_stock_to_warehouses.branch_id', NULL)
        ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
        ->select(DB::raw('sum(total_received_qty) as total_qty'))
        ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

        $received = DB::table('transfer_stock_to_branch_products')
        ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
        ->where('transfer_stock_to_branches.branch_id', NULL)
        ->where('transfer_stock_to_branch_products.product_id', $product_id)
        ->select(DB::raw('sum(total_received_qty) as total_qty'))
        ->groupBy('transfer_stock_to_branch_products.product_id')->get();

        $adjustment = DB::table('stock_adjustment_products')
        ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
        ->where('stock_adjustments.branch_id', NULL)
        ->where('stock_adjustments.warehouse_id', NULL)
        ->where('stock_adjustment_products.product_id', $product_id)
        ->select(DB::raw('sum(quantity) as total_qty'))
        ->groupBy('stock_adjustment_products.product_id')->get();

        $currentMbStock = $productOpeningStock->sum('po_stock') 
                        + $productPurchase->sum('total_purchase')
                        - $productSale->sum('total_sale')
                        + $saleReturn->sum('total_return')
                        - $supplierReturn->sum('total_return')
                        - $purchaseReturn->sum('total_return')
                        - $transferred->sum('total_qty')
                        - $adjustment->sum('total_qty')
                        + $received->sum('total_qty');

        $singleProduct = Product::where('id', $product_id)->first();
        $singleProduct->mb_stock = $currentMbStock;
        $singleProduct->save();

        if ($variant_id) {
            $productOpeningStock = DB::table('product_opening_stocks')
            ->where('product_opening_stocks.branch_id', NULL)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->select(DB::raw('sum(quantity) as po_stock'))
            ->groupBy('product_opening_stocks.product_id')->get();
    
            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sales.branch_id', NULL)
                ->where('sale_products.product_id', $product_id)
                ->where('sale_products.product_variant_id', $variant_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_variant_id')->get();
    
            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', NULL)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_variant_id')->get();
    
            $saleReturn = DB::table('sale_return_products')
                ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->where('sale_returns.branch_id', NULL)
                ->where('sale_return_products.product_id', $product_id)
                ->where('sale_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('sale_return_products.product_variant_id')->get();
    
            $purchaseReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', NULL)
                ->where('purchases.warehouse_id', NULL)
                ->where('purchase_return_products.product_id', $product_id)
                ->where('purchase_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_variant_id')->get();
    
            $supplierReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->where('purchase_returns.purchase_id', NULL)
                ->where('purchase_returns.branch_id', NULL)
                ->where('purchase_returns.warehouse_id', NULL)
                ->where('purchase_return_products.product_id', $product_id)
                ->where('purchase_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_variant_id')->get();
    
            $transferred = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.branch_id', NULL)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();
    
            $received = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.branch_id', NULL)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->where('stock_adjustments.branch_id', NULL)
                ->where('stock_adjustments.warehouse_id', NULL)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->where('stock_adjustment_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_variant_id')->get();
        
            $currentMbStock = $productOpeningStock->sum('po_stock') 
                            + $productPurchase->sum('total_purchase')
                            - $productSale->sum('total_sale')
                            + $saleReturn->sum('total_return')
                            - $supplierReturn->sum('total_return')
                            - $purchaseReturn->sum('total_return')
                            - $transferred->sum('total_qty')
                            - $adjustment->sum('total_qty')
                            + $received->sum('total_qty');
    
            $variantProduct = ProductVariant::where('id', $variant_id)->first();
            $variantProduct->mb_stock = $currentMbStock;
            $variantProduct->save();
        }
    }

    public function adjustBranchStock($product_id, $variant_id, $branch_id)
    {
        $productOpeningStock = DB::table('product_opening_stocks')
        ->where('product_opening_stocks.branch_id', $branch_id)
        ->where('product_id', $product_id)
        ->select(DB::raw('sum(quantity) as po_stock'))
        ->groupBy('product_opening_stocks.product_id')->get();

        $productSale = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
            ->where('sales.branch_id', $branch_id)
            ->where('sale_products.product_id', $product_id)
            ->where('sales.status', 1)
            ->select(DB::raw('sum(quantity) as total_sale'))
            ->groupBy('sale_products.product_id')->get();

        $productPurchase = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->where('purchases.branch_id', $branch_id)
            ->where('purchase_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_purchase'))
            ->groupBy('purchase_products.product_id')->get();

        $saleReturn = DB::table('sale_return_products')
            ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
            ->where('sale_returns.branch_id', $branch_id)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('sale_return_products.product_id')->get();

        $purchaseReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->where('purchases.branch_id', $branch_id)
            ->where('purchases.warehouse_id', NULL)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $supplierReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->where('purchase_returns.purchase_id', NULL)
            ->where('purchase_returns.branch_id', $branch_id)
            ->where('purchase_returns.warehouse_id', NULL)
            ->where('product_id', $product_id)->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $transferred = DB::table('transfer_stock_to_warehouse_products')
            ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
            ->where('transfer_stock_to_warehouses.branch_id', $branch_id)
            ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
            ->select(DB::raw('sum(total_received_qty) as total_qty'))
            ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

        $received = DB::table('transfer_stock_to_branch_products')
            ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
            ->where('transfer_stock_to_branches.branch_id', $branch_id)
            ->where('transfer_stock_to_branch_products.product_id', $product_id)
            ->select(DB::raw('sum(total_received_qty) as total_qty'))
            ->groupBy('transfer_stock_to_branch_products.product_id')->get();

        $adjustment = DB::table('stock_adjustment_products')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
            ->where('stock_adjustments.branch_id', $branch_id)
            ->where('stock_adjustments.warehouse_id', NULL)
            ->where('stock_adjustment_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_qty'))
            ->groupBy('stock_adjustment_products.product_id')->get();

        $currentMbStock = $productOpeningStock->sum('po_stock') 
                        + $productPurchase->sum('total_purchase')
                        - $productSale->sum('total_sale')
                        + $saleReturn->sum('total_return')
                        - $supplierReturn->sum('total_return')
                        - $purchaseReturn->sum('total_return')
                        - $transferred->sum('total_qty')
                        - $adjustment->sum('total_qty')
                        + $received->sum('total_qty');

        $productBranch = ProductBranch::where('branch_id', $branch_id)->where('product_id', $product_id)->first();
        $productBranch->product_quantity = $currentMbStock;
        $productBranch->save();

        if ($variant_id) {
            $productOpeningStock = DB::table('product_opening_stocks')
            ->where('product_opening_stocks.branch_id', $branch_id)
            ->where('product_opening_stocks.product_id', $product_id)
            ->where('product_opening_stocks.product_variant_id', $variant_id)
            ->select(DB::raw('sum(quantity) as po_stock'))
            ->groupBy('product_opening_stocks.product_variant_id')->get();
    
            $productSale = DB::table('sale_products')
                ->leftJoin('sales', 'sale_products.sale_id', 'sales.id')
                ->where('sales.branch_id', $branch_id)
                ->where('sale_products.product_id', $product_id)
                ->where('sale_products.product_variant_id', $variant_id)
                ->where('sales.status', 1)
                ->select(DB::raw('sum(quantity) as total_sale'))
                ->groupBy('sale_products.product_variant_id')->get();
    
            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', $branch_id)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_variant_id')->get();
    
            $saleReturn = DB::table('sale_return_products')
                ->join('sale_returns', 'sale_return_products.sale_return_id', 'sale_returns.id')
                ->where('sale_returns.branch_id', $branch_id)
                ->where('sale_return_products.product_id', $product_id)
                ->where('sale_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('sale_return_products.product_variant_id')->get();
    
            $purchaseReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', $branch_id)
                ->where('purchases.warehouse_id', NULL)
                ->where('purchase_return_products.product_id', $product_id)
                ->where('purchase_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_variant_id')->get();
    
            $supplierReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->where('purchase_returns.purchase_id', NULL)
                ->where('purchase_returns.branch_id', $branch_id)
                ->where('purchase_returns.warehouse_id', NULL)
                ->where('purchase_return_products.product_id', $product_id)
                ->where('purchase_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_variant_id')->get();
    
            $transferred = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.branch_id', $branch_id)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();
    
            $received = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.branch_id', $branch_id)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();
            
            $adjustment = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->where('stock_adjustments.branch_id', $branch_id)
                ->where('stock_adjustments.warehouse_id', NULL)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->where('stock_adjustment_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_variant_id')->get();
        
            $currentMbStock = $productOpeningStock->sum('po_stock') 
                            + $productPurchase->sum('total_purchase')
                            - $productSale->sum('total_sale')
                            + $saleReturn->sum('total_return')
                            - $supplierReturn->sum('total_return')
                            - $purchaseReturn->sum('total_return')
                            - $transferred->sum('total_qty')
                            + $received->sum('total_qty');
    
            $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();

            $productBranchVariant->variant_quantity = $currentMbStock;
            $productBranchVariant->save();
        }
    }

    public function adjustWarehouseStock($product_id, $variant_id, $warehouse_id)
    {
        $productPurchase = DB::table('purchase_products')
            ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
            ->where('purchases.warehouse_id', $warehouse_id)
            ->where('purchase_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_purchase'))
            ->groupBy('purchase_products.product_id')->get();

        $purchaseReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
            ->where('purchases.warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $supplierReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->where('purchase_returns.purchase_id', NULL)
            ->where('purchase_returns.warehouse_id', $warehouse_id)
            ->where('purchase_return_products.product_id', $product_id)
            ->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_id')->get();

        $received = DB::table('transfer_stock_to_warehouse_products')
        ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
        ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
        ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
        ->select(DB::raw('sum(total_received_qty) as total_qty'))
        ->groupBy('transfer_stock_to_warehouse_products.product_id')->get();

        $transferred = DB::table('transfer_stock_to_branch_products')
        ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
        ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
        ->where('transfer_stock_to_branch_products.product_id', $product_id)
        ->select(DB::raw('sum(total_received_qty) as total_qty'))
        ->groupBy('transfer_stock_to_branch_products.product_id')->get();

        $adjustment = DB::table('stock_adjustment_products')
            ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
            ->where('stock_adjustments.warehouse_id', $warehouse_id)
            ->where('stock_adjustment_products.product_id', $product_id)
            ->select(DB::raw('sum(quantity) as total_qty'))
            ->groupBy('stock_adjustment_products.product_id')->get();

        $currentMbStock = $productPurchase->sum('total_purchase')
                        - $purchaseReturn->sum('total_return')
                        - $supplierReturn->sum('total_return')
                        - $transferred->sum('total_qty')
                        - $adjustment->sum('total_qty')
                        + $received->sum('total_qty');

        $productWarehouse = ProductWarehouse::where('warehouse_id', $warehouse_id)->where('product_id', $product_id)->first();
        $productWarehouse->product_quantity = $currentMbStock;
        $productWarehouse->save();

        if ($variant_id) {
            $productPurchase = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.branch_id', $warehouse_id)
                ->where('purchase_products.product_id', $product_id)
                ->where('purchase_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_purchase'))
                ->groupBy('purchase_products.product_variant_id')->get();
  
            $purchaseReturn = DB::table('purchase_return_products')
                ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
                ->join('purchases', 'purchase_returns.purchase_id', 'purchases.id')
                ->where('purchases.warehouse_id', $warehouse_id)
                ->where('purchase_return_products.product_id', $product_id)
                ->where('purchase_return_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(return_qty) as total_return'))
                ->groupBy('purchase_return_products.product_variant_id')->get();

            $supplierReturn = DB::table('purchase_return_products')
            ->join('purchase_returns', 'purchase_return_products.purchase_return_id', 'purchase_returns.id')
            ->where('purchase_returns.purchase_id', NULL)
            ->where('purchase_returns.warehouse_id', $warehouse_id)
            ->where('purchase_return_products.product_id', $product_id)
            ->where('purchase_return_products.product_variant_id', $variant_id)
            ->select(DB::raw('sum(return_qty) as total_return'))
            ->groupBy('purchase_return_products.product_variant_id')->get();
 
            $received = DB::table('transfer_stock_to_warehouse_products')
                ->leftJoin('transfer_stock_to_warehouses', 'transfer_stock_to_warehouse_products.transfer_stock_id', 'transfer_stock_to_warehouses.id')
                ->where('transfer_stock_to_warehouses.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_warehouse_products.product_id', $product_id)
                ->where('transfer_stock_to_warehouse_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_warehouse_products.product_variant_id')->get();
    
            $transferred = DB::table('transfer_stock_to_branch_products')
                ->leftJoin('transfer_stock_to_branches', 'transfer_stock_to_branch_products.transfer_stock_id', 'transfer_stock_to_branches.id')
                ->where('transfer_stock_to_branches.warehouse_id', $warehouse_id)
                ->where('transfer_stock_to_branch_products.product_id', $product_id)
                ->where('transfer_stock_to_branch_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(total_received_qty) as total_qty'))
                ->groupBy('transfer_stock_to_branch_products.product_variant_id')->get();

            $adjustment = DB::table('stock_adjustment_products')
                ->leftJoin('stock_adjustments', 'stock_adjustment_products.stock_adjustment_id', 'stock_adjustments.id')
                ->where('stock_adjustments.warehouse_id', $warehouse_id)
                ->where('stock_adjustment_products.product_id', $product_id)
                ->where('stock_adjustment_products.product_variant_id', $variant_id)
                ->select(DB::raw('sum(quantity) as total_qty'))
                ->groupBy('stock_adjustment_products.product_variant_id')->get();
    
            $currentMbStock = $productPurchase->sum('total_purchase')
                            - $purchaseReturn->sum('total_return')
                            - $supplierReturn->sum('total_return')
                            - $transferred->sum('total_qty')
                            - $adjustment->sum('total_qty')
                            + $received->sum('total_qty');
    
            $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();
            
            $productWarehouseVariant->variant_quantity = $currentMbStock;
            $productWarehouseVariant->save();
        }
    }

    public function addWarehouseProduct($product_id, $variant_id, $warehouse_id)
    {
        $checkExistsProductInWarehouse = DB::table('product_warehouses')->where('warehouse_id', $warehouse_id)
        ->where('product_id', $product_id)->first();
        if (!$checkExistsProductInWarehouse) {
            $productWarehouse = new ProductWarehouse();
            $productWarehouse->warehouse_id = $warehouse_id;
            $productWarehouse->product_id = $product_id;
            $productWarehouse->save();
            if ($variant_id) {
                $productWarehouseVariant = new ProductWarehouseVariant();
                $productWarehouseVariant->product_warehouse_id = $productWarehouse->id;
                $productWarehouseVariant->product_variant_id = $variant_id;
            }
        }
    }

    public function addBranchProduct($product_id, $variant_id, $branch_id)
    {
        $checkExistsProductInBranch = DB::table('product_branches')->where('branch_id', $branch_id)
        ->where('product_id', $product_id)->first();
        if (!$checkExistsProductInBranch) {
            $productBranch = new ProductBranch();
            $productBranch->branch_id = auth()->user()->branch_id;
            $productBranch->product_id = $product_id;
            $productBranch->save();
            if ($variant_id) {
                $productBranchVariant = new ProductBranchVariant();
                $productBranchVariant->product_branch_id = $productBranch->id;
                $productBranchVariant->product_variant_id = $variant_id;
            }
        }
    }

}
