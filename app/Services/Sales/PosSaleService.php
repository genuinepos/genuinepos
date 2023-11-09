<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale;
use App\Enums\PaymentStatus;
use App\Enums\SaleScreenType;
use App\Enums\ShipmentStatus;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PosSaleService
{
    public function addPosSale(object $request, int $saleScreenType, object $codeGenerator, ?string $invoicePrefix, ?string $quotationPrefix, ?string $dateFormat): object
    {
        $transId = '';
        if ($request->status == SaleStatus::Final->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'quotation_id', prefix: $quotationPrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Draft->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'draft_id', prefix: 'DRF', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Hold->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'hold_invoice_id', prefix: 'HINV', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            $transId = $codeGenerator->generateMonthWise(table: 'sales', column: 'suspend_id', prefix: 'SPND', splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);
        }

        $addSale = new Sale();
        $addSale->invoice_id = $request->status == SaleStatus::Final->value ? $transId : null;
        $addSale->quotation_id = $request->status == SaleStatus::Quotation->value ? $transId : null;
        $addSale->draft_id = $request->status == SaleStatus::Draft->value ? $transId : null;
        $addSale->hold_invoice_id = $request->status == SaleStatus::Hold->value ? $transId : null;
        $addSale->suspend_id = $request->status == SaleStatus::Suspended->value ? $transId : null;
        $addSale->created_by_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->branch_id = auth()->user()->branch_id;
        $addSale->customer_account_id = $request->customer_account_id;
        $addSale->status = $request->status;
        $addSale->date = date($dateFormat);
        $addSale->date_ts = date('Y-m-d H:i:s');
        $addSale->sale_date_ts = $request->status == SaleStatus::Final->value ? date('Y-m-d H:i:s') : null;
        $addSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value ? date('Y-m-d H:i:s') : null;
        $addSale->draft_date_ts = $request->status == SaleStatus::Draft->value ? date('Y-m-d H:i:s') : null;
        $addSale->quotation_status = $request->status == SaleStatus::Quotation->value ? 1 : 0;
        $addSale->draft_status = $request->status == SaleStatus::Draft->value ? 1 : 0;
        $addSale->total_item = $request->total_item;
        $addSale->total_qty = $request->total_qty;
        $addSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : 0;
        $addSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
        $addSale->total_invoice_amount = $request->total_invoice_amount;
        $addSale->due = $request->total_invoice_amount;
        $addSale->sale_screen = $saleScreenType;
        $addSale->save();

        return $addSale;
    }
}
