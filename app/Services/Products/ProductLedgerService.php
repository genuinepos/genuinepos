<?php

namespace App\Services\Products;

use App\Models\Products\ProductLedger;

class ProductLedgerService
{
    public static function voucherTypes()
    {
        return  [
            0 => 'Opening Stock',
            1 => 'Sales',
            2 => 'Sales Return',
            3 => 'Purchase',
            4 => 'Purchase Return',
            5 => 'Stock Adjustment',
            6 => 'Production',
        ];
    }

    public function voucherType($voucherTypeId)
    {
        $data = [
            0 => ['name' => 'Opening Stock', 'id' => 'opening_stock_product_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'opening_stock_id', 'link' => null],
            1 => ['name' => 'Sales', 'id' => 'sale_product_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            3 => ['name' => 'Purchase', 'id' => 'purchase_product_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            4 => ['name' => 'Purchase Return', 'id' => 'purchase_return_product_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchases.returns.show'],
            5 => ['name' => 'Stock Adjustment', 'id' => 'stock_adjustment_product_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'stock_adjustment_id', 'link' => 'stock.adjustments.show'],
            6 => ['name' => 'Production', 'id' => 'production_id', 'voucher_no' => 'product_voucher', 'details_id' => 'production_id', 'link' => null],
        ];

        return $data[$voucherTypeId];
    }

    public function addProductLedgerEntry(
        int $voucherTypeId,
        string $date,
        int $productId,
        int $transId,
        float $rate,
        string $quantityType,
        float $quantity,
        float $subtotal,
        ?int $variantId = null,
        ?int $branchId = null,
        ?int $warehouseId = null,
    ) {
        $voucherType = $this->voucherType($voucherTypeId);
        $add = new ProductLedger();
        $add->branch_id = $branchId ? $branchId : auth()->user()->branch_id;
        $add->warehouse_id = $warehouseId;
        $add->date = $date;
        $add->date_ts = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $add->product_id = $productId;
        $add->variant_id = $variantId ? $variantId : null;
        $add->voucher_type = $voucherTypeId;
        $add->{$voucherType['id']} = $transId;
        $add->rate = $rate;
        $add->{$quantityType} = $quantity;
        $add->subtotal = $subtotal;
        $add->type = $quantityType;
        $add->save();
    }

    public function updateProductLedgerEntry(
        int $voucherTypeId,
        string $date,
        int $productId,
        int $transId,
        float $rate,
        string $quantityType,
        float $quantity,
        float $subtotal,
        ?int $variantId = null,
        ?int $branchId = null,
        ?int $warehouseId = null,
        ?int $currentWarehouseId = null,
    ) {
        $branchId = $branchId ? $branchId : auth()->user()->branch_id;
        $voucherType = $this->voucherType($voucherTypeId);

        $update = '';
        $query = ProductLedger::where($voucherType['id'], $transId)
            ->where('voucher_type', $voucherTypeId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->where('branch_id', $branchId);

        if ($currentWarehouseId) {

            $query->where('warehouse_id', $currentWarehouseId);
        }

        $update = $query->first();

        if ($update) {

            $update->warehouse_id = $warehouseId;
            $update->date = $date;
            $previousTime = date(' H:i:s', strtotime($update->date));
            $update->date_ts = date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $update->product_id = $productId;
            $update->variant_id = $variantId ? $variantId : null;
            $update->voucher_type = $voucherTypeId;
            $update->rate = $rate;
            $update->{$quantityType} = $quantity;
            $update->subtotal = $subtotal;
            $update->type = $quantityType;
            $update->save();
        } else {

            $this->addProductLedgerEntry(voucherTypeId: $voucherTypeId, date: $date, productId: $productId, transId: $transId, rate: $rate, quantityType: $quantityType, quantity: $quantity, subtotal: $subtotal, variantId: $variantId, branchId: $branchId, warehouseId: $warehouseId);
        }
    }
}
