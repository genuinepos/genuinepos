<?php

namespace App\Services\Purchases;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Purchases\Purchase;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderService
{
    public function addPurchaseOrder(object $request, object $codeGenerator, string $invoicePrefix): ?object
    {
        $invoiceId = $codeGenerator->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::PurchaseOrder->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $invoiceId;
        $addPurchase->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $addPurchase->branch_id = auth()->user()->branch_id;
        $addPurchase->supplier_account_id = $request->supplier_account_id;
        $addPurchase->purchase_account_id = $request->purchase_account_id;
        $addPurchase->pay_term = $request->pay_term;
        $addPurchase->pay_term_number = $request->pay_term_number;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->total_qty = $request->total_qty;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_ordered_amount;
        $addPurchase->paid = $request->paying_amount;
        $addPurchase->due = $request->total_ordered_amount;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->order_note;
        $addPurchase->purchase_status = PurchaseStatus::PurchaseOrder->value;
        $addPurchase->is_purchased = 1;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchase->is_last_created = 1;
        $addPurchase->save();

        return $addPurchase;
    }

    public function restrictions(object $request, bool $checkSupplierChangeRestriction = false, ?int $purchaseId = null): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase order products must be less than 60 or equal.")];
        }

        if ($checkSupplierChangeRestriction == true) {

            $purchase = $this->singlePurchaseOrder(id: $purchaseId, with: ['references']);

            if (count($purchase->references)) {

                if ($purchase->supplier_account_id != $request->supplier_account_id) {

                    return ['pass' => false, 'msg' => __("Supplier can not be changed. One or more payments is exists against this purchase order.")];
                }
            }
        }

        return ['pass' => true];
    }

    public function singlePurchaseOrder(int $id, ?array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
