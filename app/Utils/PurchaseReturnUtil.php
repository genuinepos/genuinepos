<?php

namespace App\Utils;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductBranch;
use App\Models\ProductVariant;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProduct;
use App\Models\ProductWarehouse;
use App\Models\ProductBranchVariant;
use App\Models\PurchaseReturnProduct;
use App\Models\ProductWarehouseVariant;
use Illuminate\Support\Facades\DB;

class PurchaseReturnUtil
{
    public function updateProductStock($request)
    {
        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $return_quantities = $request->return_quantities;

        $index = 0;
        foreach ($product_ids as $product_id) {
            //update product qty
            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : NULL;
            $product = Product::where('id', $product_id)->first();
            $product->quantity -= (float)$return_quantities[$index];
            $product->save();

            // update product variant qty
            $productVariant = ProductVariant::where('id', $variant_id)->first();
            if ($productVariant) {
                $productVariant->variant_quantity -= (float)$return_quantities[$index];
                $productVariant->save();
            }

            if (isset($request->warehouse_id)) {
                //update product warehouse qty
                $productWarehouse = ProductWarehouse::where('warehouse_id', $request->warehouse_id)
                    ->where('product_id', $product_id)->first();
                $productWarehouse->product_quantity -= (float)$return_quantities[$index];
                $productWarehouse->save();

                // Update product warehouse variant qty
                $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)->first();

                if ($productWarehouseVariant) {
                    $productWarehouseVariant->variant_quantity -= (float)$return_quantities[$index];
                    $productWarehouseVariant->save();
                }
            } else {
                if (auth()->user()->branch_id) {
                    //update product branch qty
                    $productBranch = ProductBranch::where('branch_id', auth()->user()->branch_id)
                        ->where('product_id', $product_id)->first();
                    $productBranch->product_quantity -= (float)$return_quantities[$index];
                    $productBranch->save();

                    // Update product branch variant qty
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)
                        ->where('product_id', $product_id)
                        ->where('product_variant_id', $variant_id)->first();

                    if ($productBranchVariant) {
                        $productBranchVariant->variant_quantity -= (float)$return_quantities[$index];
                        $productBranchVariant->save();
                    }
                } else {
                    $MbStock = Product::where('id', $product_id)->first();
                    $MbStock->mb_stock -= (float)$return_quantities[$index];
                    $MbStock->save();

                    if ($variant_ids[$index] != 'noid') {
                        $ProVariantMbStock = ProductVariant::where('id', $variant_ids[$index])
                            ->where('product_id', $product_id)->first();
                        $ProVariantMbStock->mb_stock -= (float)$return_quantities[$index];
                        $ProVariantMbStock->save();
                    }
                }
            }
        }
    }

    public function updateProductStockForAdjustment($updatePurchaseReturn)
    {
        foreach ($updatePurchaseReturn->purchase_return_products as $purchase_return_product) {
            $purchase_return_product->is_delete_in_update = 1;
            $purchase_return_product->save();

            // Update product qty for adjustment 
            $product = Product::where('id', $purchase_return_product->product_id)->first();
            $product->quantity += $purchase_return_product->return_qty;
            $product->save();

            if ($updatePurchaseReturn->product_variant_id) {
                // Update product variant qty for adjustment 
                $productVariant = ProductVariant::where('id', $updatePurchaseReturn->product_variant_id)->first();
                $productVariant->variant_quantity += $purchase_return_product->return_qty;
                $productVariant->save();
            }

            if ($updatePurchaseReturn->warehouse_id) {
                // Update product warehouse qty for adjustment 
                $productWarehouse = ProductWarehouse::where('warehouse_id', $updatePurchaseReturn->warehouse_id)->where('product_id', $purchase_return_product->product_id)->first();
                $productWarehouse->product_quantity += $purchase_return_product->return_qty;
                $productWarehouse->save();

                // Update product variant qty for adjustment 
                if ($updatePurchaseReturn->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_variant_id', $updatePurchaseReturn->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity += $purchase_return_product->return_qty;
                    $productWarehouseVariant->save();
                }
            } elseif ($updatePurchaseReturn->branch_id) {
                // Update product warehouse qty for adjustment 
                $productBranch = ProductBranch::where('branch_id', $updatePurchaseReturn->branch_id)->where('product_id', $purchase_return_product->product_id)->first();
                $productBranch->product_quantity += $purchase_return_product->return_qty;
                $productBranch->save();

                // Update product variant qty for adjustment 
                if ($updatePurchaseReturn->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_variant_id', $updatePurchaseReturn->product_variant_id)->first();
                    $productBranchVariant->variant_quantity += $purchase_return_product->return_qty;
                    $productBranchVariant->save();
                }
            } else {
                $MbStock = Product::where('id', $purchase_return_product->product_id)->first();
                $MbStock->mb_stock += $purchase_return_product->return_qty;
                $MbStock->save();

                if ($purchase_return_product->product_variant_id) {
                    $updateProVariantMbStock = ProductVariant::where('id', $purchase_return_product->product_variant_id)
                        ->where('product_id', $purchase_return_product->product_id)
                        ->first();
                    $updateProVariantMbStock->mb_stock += $purchase_return_product->return_qty;
                    $updateProVariantMbStock->save();
                }
            }
        }
    }

    public function updatePurchaseInvoiceWiseReturn($purchaseId, $purchaseReturn, $request, $invoicePrefix, $invoiceId)
    {
        $purchase_product_ids = $request->purchase_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;

        $purchase = Purchase::where('id', $purchaseId)->first();
        //Update purchase and supplier purchase return due
        $purchaseDue = $purchase->total_purchase_amount - $purchase->paid;
        $purchaseReturnDue = $request->total_return_amount - $purchaseDue;
        if ($purchaseReturnDue >= 0) {
            $acReturnDue = $purchaseReturnDue - $purchaseReturn->total_return_due_received;
            $purchase->purchase_return_due = $acReturnDue;
        } else {
            $purchase->purchase_return_due = 0.00;
        }

        $purchase->due = $purchaseDue - $request->total_return_amount;
        $purchase->purchase_return_amount = $request->total_return_amount;
        $purchase->is_return_available = 1;
        $purchase->save();

 
        //Adjust Quantity 
        foreach ($purchaseReturn->purchase_return_products as $purchase_return_product) {
            // Addition purchase product for adjustment
            $purchaseProduct = PurchaseProduct::where('id', $purchase_return_product->purchase_product_id)->first();

            //Addition product qty for adjustment
            $product = Product::where('id', $purchaseProduct->product_id)->first();
            $product->quantity += $purchase_return_product->return_qty;
            $product->save();

            //Addition product variant qty for adjustment
            if ($purchaseProduct->product_variant_id) {
                $productVariant = ProductVariant::where('id', $purchaseProduct->product_variant_id)->first();
                $productVariant->variant_quantity += $purchase_return_product->return_qty;
                $productVariant->save();
            }

            if ($purchase->warehouse_id) {
                // Addition product warehouse qty for adjustment
                $productWarehouse = ProductWarehouse::where('warehouse_id', $purchase->warehouse_id)->where('product_id', $purchaseProduct->product_id)->first();
                $productWarehouse->product_quantity += $purchase_return_product->return_qty;
                $productWarehouse->save();

                // Addition product warehouse variant qty for adjustment
                if ($purchaseProduct->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity += $purchase_return_product->return_qty;
                    $productWarehouseVariant->save();
                }
            } elseif ($purchase->branch_id) {
                // Addition product branch qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->first();
                $productBranch->product_quantity += $purchase_return_product->return_qty;
                $productBranch->save();

                // Addition product warehouse variant qty for adjustment
                if ($purchaseProduct->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productBranchVariant->variant_quantity += $purchase_return_product->return_qty;
                    $productBranchVariant->save();
                }
            } else {
                $MbStock = Product::where('id', $purchaseProduct->product_id)->first();
                $MbStock->mb_stock += $purchase_return_product->return_qty;
                $MbStock->save();

                if ($purchaseProduct->product_variant_id) {
                    $updateProVariantMbStock = ProductVariant::where('id', $purchaseProduct->product_variant_id)
                        ->where('product_id', $purchaseProduct->product_id)
                        ->first();
                    $updateProVariantMbStock->mb_stock += $purchase_return_product->return_qty;
                    $updateProVariantMbStock->save();
                }
            }
        }

        // Update purchase return
        $purchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PRI') . date('ymd') . $invoiceId;

        if ($purchase->warehouse_id) {
            $purchaseReturn->warehouse_id = $purchase->warehouse_id;
        } else {
            $purchaseReturn->branch_id = $purchase->branch_id;
        }

        $purchaseReturn->supplier_id = $purchase->supplier_id;
        $purchaseReturn->total_return_amount = $request->total_return_amount;

        if ($purchaseReturnDue > 0) {
            $purchaseReturn->total_return_due = $purchaseReturnDue - $purchaseReturn->total_return_due_received;
        }

        $purchaseReturn->date = $request->date;
        $purchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $purchaseReturn->save();

        // update purchase return products stock
        $index = 0;
        foreach ($purchase_product_ids as $purchase_product_id) {
            // Update purchase product quantity for adjustment
            $purchaseProduct = PurchaseProduct::where('id', $purchase_product_id)->first();

            // Update product quantity for adjustment
            $product = Product::where('id', $purchaseProduct->product_id)->first();
            $product->quantity -= (float)$return_quantities[$index];
            $product->save();
            // Update product variant quantity for adjustment
            if ($purchaseProduct->product_variant_id) {
                $productVariant = ProductVariant::where('id', $purchaseProduct->product_variant_id)->first();
                $productVariant->variant_quantity -= (float)$return_quantities[$index];
                $product->save();
            }

            if ($purchase->warehouse_id) {
                // Addition product warehouse qty for adjustment
                $productWarehouse = ProductWarehouse::where('warehouse_id', $purchase->warehouse_id)->where('product_id', $purchaseProduct->product_id)->first();
                $productWarehouse->product_quantity -= (float)$return_quantities[$index];
                $productWarehouse->save();

                // Addition product warehouse variant qty for adjustment
                if ($purchaseProduct->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity -= (float)$return_quantities[$index];
                    $productWarehouseVariant->save();
                }
            } elseif ($purchase->branch_id) {
                // Addition product branch qty for adjustment
                $productBranch = ProductBranch::where('branch_id', $purchase->branch_id)
                    ->where('product_id', $purchaseProduct->product_id)->first();
                $productBranch->product_quantity -= (float)$return_quantities[$index];
                $productBranch->save();

                // Addition product branch variant qty for adjustment
                if ($purchaseProduct->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $purchaseProduct->product_id)
                        ->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productBranchVariant->variant_quantity -= (float)$return_quantities[$index];
                    $productBranchVariant->save();
                }
            } else {
                $MbStock = Product::where('id', $purchaseProduct->product_id)->first();
                $MbStock->mb_stock -= (float)$return_quantities[$index];
                $MbStock->save();

                if ($purchaseProduct->product_variant_id) {
                    $updateProVariantMbStock = ProductVariant::where('id', $purchaseProduct->product_variant_id)
                        ->where('product_id', $purchaseProduct->product_id)
                        ->first();
                    $updateProVariantMbStock->mb_stock -= (float)$return_quantities[$index];
                    $updateProVariantMbStock->save();
                }
            }

            $returnProduct = PurchaseReturnProduct::where('purchase_return_id', $purchaseReturn->id)
                ->where('purchase_product_id', $purchase_product_id)->first();

            $returnProduct->return_qty = $return_quantities[$index];
            $returnProduct->unit = $units[$index];
            $returnProduct->return_subtotal = $return_subtotals[$index];
            $returnProduct->save();
            $index++;
        }
    }

    public function storePurchaseInvoiceWiseReturn($purchaseId, $request, $invoicePrefix, $invoiceId)
    {
        $purchase_product_ids = $request->purchase_product_ids;
        $return_quantities = $request->return_quantities;
        $return_subtotals = $request->return_subtotals;
        $units = $request->units;
        $purchase = Purchase::where('id', $purchaseId)->first();
        //Update purchase and supplier return due
        $purchaseDue = $purchase->total_purchase_amount - $purchase->paid;
        $purchaseReturnDue = $request->total_return_amount - $purchaseDue;
        // if ($purchaseReturnDue >= 0) {
        //     $purchase->purchase_return_due = $purchaseReturnDue;
        // } else {
        //     $purchase->purchase_return_due = 0.00;
        // }

        // $purchase->due = $purchaseDue - $request->total_return_amount;
        // $purchase->purchase_return_amount = $request->total_return_amount;
        // $purchase->is_return_available = 1;
        // $purchase->save();

        $addPurchaseReturn = new PurchaseReturn();
        $addPurchaseReturn->purchase_id = $purchase->id;
        $addPurchaseReturn->invoice_id = $request->invoice_id ? $request->invoice_id : ($invoicePrefix != null ? $invoicePrefix : 'PRI') . date('ymd') . $invoiceId;
        if ($purchase->warehouse_id) {
            $addPurchaseReturn->warehouse_id = $purchase->warehouse_id;
        } else {
            $addPurchaseReturn->branch_id = $purchase->branch_id;
        }

        $addPurchaseReturn->supplier_id = $purchase->supplier_id;
        $addPurchaseReturn->admin_id = auth()->user()->id;
        $addPurchaseReturn->total_return_amount = $request->total_return_amount;
        if ($purchaseReturnDue > 0) {
            $addPurchaseReturn->total_return_due = $purchaseReturnDue;
        }
        $addPurchaseReturn->return_type = 1;
        $addPurchaseReturn->date = $request->date;
        $addPurchaseReturn->report_date = date('Y-m-d', strtotime($request->date));
        $addPurchaseReturn->month = date('F');
        $addPurchaseReturn->year = date('Y');
        $addPurchaseReturn->save();

        // Add purchase return products
        $index = 0;
        foreach ($purchase_product_ids as $purchase_product_id) {
            // Update purchase product quantity for adjustment
            $purchaseProduct = PurchaseProduct::where('id', $purchase_product_id)->first();
            // Update product quantity
            $product = Product::where('id', $purchaseProduct->product_id)->first();
            $product->quantity -= (float)$return_quantities[$index];
            $product->save();

            // Update product variant quantity
            if ($purchaseProduct->product_variant_id) {
                $productVariant = ProductVariant::where('id', $purchaseProduct->product_variant_id)->first();
                $productVariant->variant_quantity -= (float)$return_quantities[$index];
                $product->save();
            }

            if ($purchase->warehouse_id) {
                // Update product warehouse quantity for adjustment
                $productWarehouse = ProductWarehouse::where('warehouse_id', $purchase->warehouse_id)->where('product_id', $purchaseProduct->product_id)->first();
                $productWarehouse->product_quantity -= (float)$return_quantities[$index];
                $productWarehouse->save();

                if ($purchaseProduct->product_variant_id) {
                    $productWarehouseVariant = ProductWarehouseVariant::where('product_warehouse_id', $productWarehouse->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productWarehouseVariant->variant_quantity -= (float)$return_quantities[$index];
                    $productWarehouseVariant->save();
                }
            } elseif ($purchase->branch_id) {
                // Update product branch quantity for adjustment
                $productBranch = ProductBranch::where('branch_id', $purchase->branch_id)->where('product_id', $purchaseProduct->product_id)->first();
                $productBranch->product_quantity -= (float)$return_quantities[$index];
                $productBranch->save();

                if ($purchaseProduct->product_variant_id) {
                    $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $purchaseProduct->product_id)->where('product_variant_id', $purchaseProduct->product_variant_id)->first();
                    $productBranchVariant->variant_quantity -= (float)$return_quantities[$index];
                    $productBranchVariant->save();
                }
            } else {
                $MbStock = Product::where('id', $purchaseProduct->product_id)->first();
                $MbStock->mb_stock -= (float)$return_quantities[$index];
                $MbStock->save();

                if ($purchaseProduct->product_variant_id) {
                    $updateProVariantMbStock = ProductVariant::where('id', $purchaseProduct->product_variant_id)
                        ->where('product_id', $purchaseProduct->product_id)
                        ->first();
                    $updateProVariantMbStock->mb_stock -= (float)$return_quantities[$index];
                    $updateProVariantMbStock->save();
                }
            }

            $addReturnProduct = new PurchaseReturnProduct();
            $addReturnProduct->purchase_return_id = $addPurchaseReturn->id;
            $addReturnProduct->purchase_product_id = $purchase_product_id;
            $addReturnProduct->product_id = $product->id;
            $addReturnProduct->product_variant_id = $purchaseProduct->product_variant_id ? $purchaseProduct->product_variant_id : NULL;
            $addReturnProduct->return_qty = $return_quantities[$index];
            $addReturnProduct->unit = $units[$index];
            $addReturnProduct->return_subtotal = $return_subtotals[$index];
            $addReturnProduct->save();
            $index++;
        }
    }

    public function adjustPurchaseInvoiceAmounts($purchase)
    {
        
        // $totalPaid = DB::table('purchase_payments')
        // ->where('purchase_payments.purchase_id', $purchase->id)
        // ->where('payment_type', 1)
        // ->select(DB::table('sum(paid_amount) as total_paid'))
        // ->groupBy('purchase_payments.purchase_id')
        // ->get();

        // $purchaseReturn = DB::table('purchase_returns')->where('purchase_id', $purchase->id)->get(['total_return_amount'])->first();
        // $returnAmount = $purchaseReturn ? $purchaseReturn->total_return_amount : 0;

        // $totalDue = $purchase->total_purchase_amount - $totalPaid - $returnAmount;
        // $returnDue = $returnAmount - $purchase->total_purchase_amount - $totalPaid;
        // $purchase->paid = $totalPaid;
        // $purchase->due = $totalDue;
        // $purchase->purchase_return_amount = $returnAmount;
        // $purchase->purchase_return_due = $returnDue > 0 ? $returnDue : 0;

    }
}
