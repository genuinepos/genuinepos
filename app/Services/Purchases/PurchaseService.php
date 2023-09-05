<?php

namespace App\Services\Purchases;

use App\Enums\PurchaseStatus;
use App\Models\Purchases\Purchase;
use Yajra\DataTables\Facades\DataTables;

class PurchaseService
{
    public function addPurchase(object $request, object $codeGenerationService, string $invoicePrefix): ?object
    {
        $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : 'PI';
        $invoiceId = $codeGenerationService->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: PurchaseStatus::Purchase->value, prefix: $__invoicePrefix, splitter: '-', suffixSeparator: '-');

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
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->purchase_tax_ac_id = $request->purchase_tax_ac_id;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_purchase_amount;
        $addPurchase->paid = $request->paying_amount;
        $addPurchase->due = $request->purchase_due;
        $addPurchase->shipment_details = $request->shipment_details;
        $addPurchase->purchase_note = $request->purchase_note;
        $addPurchase->purchase_status = PurchaseStatus::Purchase->value;
        $addPurchase->is_purchased = 1;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addPurchase->is_last_created = 1;
        $addPurchase->save();

        return $addPurchase;
    }

    public function adjustPurchaseInvoiceAmounts(object $purchase): ?object
    {
        $totalPurchasePaid = DB::table('accounting_voucher_description_references')
            ->where('accounting_voucher_description_references.purchase_id', $purchase->id)
            ->select(DB::raw('sum(accounting_voucher_description_references.amount) as total_paid'))
            ->groupBy('accounting_voucher_description_references.purchase_id')
            ->get();

        $totalReturn = DB::table('purchase_returns')
            ->where('purchase_returns.purchase_id', $purchase->id)
            ->select(DB::raw('sum(total_return_amount) as total_returned_amount'))
            ->groupBy('purchase_returns.purchase_id')
            ->get();

        $due = $purchase->total_purchase_amount
            - $totalPurchasePaid->sum('total_paid')
            - $totalReturn->sum('total_returned_amount');

        $purchase->paid = $totalPurchasePaid->sum('total_paid');
        $purchase->due = $due;
        $purchase->purchase_return_amount = $totalReturn->sum('total_returned_amount');
        $purchase->save();

        return $purchase;
    }

    public function purchaseByAnyConditions(?array $with = null): ?object
    {
        $query = Purchase::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function restrictions(object $request): array
    {
        if (!isset($request->product_ids)) {

            return ['pass' => false, 'msg' => __("Product table is empty.")];
        } elseif (count($request->product_ids) > 60) {

            return ['pass' => false, 'msg' => __("Purchase invoice items must be less than 60 or equal.")];
        }

        return ['pass', true];
    }
}
