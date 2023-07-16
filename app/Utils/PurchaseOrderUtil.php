<?php

namespace App\Utils;

use App\Models\Purchase;
use App\Models\PurchaseProduct;

class PurchaseOrderUtil
{
    function addPurchaseOrder($request, $invoiceVoucherRefIdUtil, $purchaseOrderIdPrefix)
    {
        $poId = $purchaseOrderIdPrefix . str_pad($invoiceVoucherRefIdUtil->getLastId('purchases'), 5, "0", STR_PAD_LEFT);
        $addOrder = new Purchase();
        $addOrder->invoice_id = $poId;
        $addOrder->supplier_id = $request->supplier_id;
        $addOrder->purchase_account_id = $request->purchase_account_id;
        $addOrder->admin_id = auth()->user()->id;
        $addOrder->total_item = $request->total_item;
        $addOrder->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addOrder->order_discount_type = $request->order_discount_type;
        $addOrder->order_discount_amount = $request->order_discount_amount;
        $addOrder->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $addOrder->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addOrder->net_total_amount = $request->net_total_amount;
        $addOrder->total_purchase_amount = $request->total_ordered_amount;
        $addOrder->shipment_details = $request->shipment_details;
        $addOrder->purchase_note = $request->order_note;
        $addOrder->purchase_status = 3;
        $addOrder->is_purchased = 0;
        $addOrder->po_qty = $request->total_qty;
        $addOrder->po_pending_qty = $request->total_qty;
        $addOrder->po_receiving_status = 'Pending';
        $addOrder->date = $request->date;
        $addOrder->delivery_date = $request->delivery_date;
        $addOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addOrder->time = date('h:i:s a');
        $addOrder->is_last_created = 1;
        $addOrder->save();

        return $addOrder;
    }
}
