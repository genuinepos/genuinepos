<?php

namespace App\Services\TransferStocks;

use App\Enums\IsDeleteInUpdate;
use App\Models\TransferStocks\TransferStockProduct;

class TransferStockProductService
{
    public function addTransferStockProduct(object $request, int $transferStockId, int $index): object
    {
        $addTransferStockProduct = new TransferStockProduct();
        $addTransferStockProduct->transfer_stock_id = $transferStockId;
        $addTransferStockProduct->product_id = $request->product_ids[$index];
        $addTransferStockProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addTransferStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addTransferStockProduct->subtotal = $request->subtotals[$index];
        $addTransferStockProduct->send_qty = $request->quantities[$index];
        $addTransferStockProduct->pending_qty = $request->quantities[$index];
        $addTransferStockProduct->unit_id = $request->unit_ids[$index];
        $addTransferStockProduct->save();

        return $addTransferStockProduct;
    }

    public function updateTransferStockProduct(object $request, int $transferStockId, int $index): object
    {
        $addOrUpdateTransferStockProduct = '';
        $transferStockProduct = $this->singleTransferStockProduct(id: $request->transfer_stock_product_ids[$index]);

        if ($transferStockProduct) {

            $addOrUpdateTransferStockProduct = $transferStockProduct;
        } else {

            $addOrUpdateTransferStockProduct = new TransferStockProduct();
        }

        $addOrUpdateTransferStockProduct->transfer_stock_id = $transferStockId;
        $addOrUpdateTransferStockProduct->product_id = $request->product_ids[$index];
        $addOrUpdateTransferStockProduct->variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
        $addOrUpdateTransferStockProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$index];
        $addOrUpdateTransferStockProduct->subtotal = $request->subtotals[$index];
        $addOrUpdateTransferStockProduct->send_qty = $request->quantities[$index];
        $receivedQty = $transferStockProduct ? $transferStockProduct->received_qty : 0;
        $calcPendingQty = $addOrUpdateTransferStockProduct->send_qty - $receivedQty;
        $addOrUpdateTransferStockProduct->pending_qty = $calcPendingQty;
        $addOrUpdateTransferStockProduct->unit_id = $request->unit_ids[$index];
        $addOrUpdateTransferStockProduct->is_delete_in_update = IsDeleteInUpdate::No->value;
        $addOrUpdateTransferStockProduct->save();

        return $addOrUpdateTransferStockProduct;
    }

    public function updateTransferStockProductQty(object $request, int $transferStockProductId, int $index) : object
    {
        $transferStockProduct = $this->singleTransferStockProduct(id: $transferStockProductId);
        $receivedQty = $request->received_quantities[$index] ? $request->received_quantities[$index] : 0;
        $transferStockProduct->received_qty = $receivedQty;

        $calcPendingQty = $transferStockProduct->send_qty - $transferStockProduct->received_qty;

        $transferStockProduct->pending_qty = $calcPendingQty;
        $transferStockProduct->save();

        $receivedSubtotal = $transferStockProduct->unit_cost_inc_tax * $transferStockProduct->received_qty;

        $transferStockProduct->received_subtotal = $receivedSubtotal;

        return $transferStockProduct;
    }

    public function singleTransferStockProduct(?int $id, array $with = null)
    {
        $query = TransferStockProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function transferStockProducts(array $with = null)
    {
        $query = TransferStockProduct::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
