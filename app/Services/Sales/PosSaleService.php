<?php

namespace App\Services\Sales;

use Carbon\Carbon;
use App\Enums\SaleStatus;
use App\Enums\BooleanType;
use App\Models\Sales\Sale as PosSale;
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

        $addSale = new PosSale();
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
        $addSale->quotation_status = $request->status == SaleStatus::Quotation->value ? BooleanType::True->value : 0;
        $addSale->draft_status = $request->status == SaleStatus::Draft->value ? BooleanType::True->value : 0;
        $addSale->total_item = $request->total_item ? $request->total_item : 0;
        $addSale->total_qty = $request->total_qty ? $request->total_qty : 0;
        $addSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : 0;
        $addSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount ? $request->order_discount : 0;
        $addSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $addSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0;
        $addSale->total_invoice_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $addSale->due = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $addSale->sale_screen = $saleScreenType;
        $addSale->save();

        return $addSale;
    }

    public function updatePosSale(object $updateSale, object $request, object $codeGenerator, ?string $invoicePrefix, ?string $quotationPrefix, ?string $dateFormat): object
    {
        foreach ($updateSale->saleProducts as $saleProduct) {

            $saleProduct->is_delete_in_update = BooleanType::True->value;
            $saleProduct->save();
        }

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

        $updateSale->invoice_id = $request->status == SaleStatus::Final->value && !isset($updateSale->invoice_id) ? $transId : $updateSale->invoice_id;
        $updateSale->quotation_id = ($request->status == SaleStatus::Quotation->value && !isset($updateSale->quotation_id) ? $transId : $updateSale->quotation_id);
        $updateSale->draft_id = $request->status == SaleStatus::Draft->value && !isset($updateSale->draft_id) ? $transId : $updateSale->draft_id;
        $updateSale->hold_invoice_id = $request->status == SaleStatus::Hold->value && !isset($updateSale->hold_invoice_id) ? $transId : $updateSale->hold_invoice_id;
        $updateSale->suspend_id = $request->status == SaleStatus::Suspended->value && !isset($updateSale->suspend_id) ? $transId : $updateSale->suspend_id;
        $updateSale->customer_account_id = $request->customer_account_id;
        $updateSale->status = $request->status;
        $updateSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value && !isset($updateSale->quotation_date_ts) ? date('Y-m-d H:i:s') : $updateSale->quotation_date_ts;
        $updateSale->sale_date_ts = $request->status == SaleStatus::Final->value && !isset($updateSale->sale_date_ts) ? date('Y-m-d H:i:s') : $updateSale->sale_date_ts;
        $updateSale->draft_date_ts = $request->status == SaleStatus::Draft->value && !isset($updateSale->draft_date_ts) ? date('Y-m-d H:i:s') : $updateSale->draft_date_ts;
        $updateSale->draft_status = $request->status == SaleStatus::Draft->value ? BooleanType::True->value : $updateSale->draft_status;
        $updateSale->quotation_status = $request->status == SaleStatus::Quotation->value ? BooleanType::True->value : $updateSale->quotation_status;
        $updateSale->total_item = $request->total_item ? $request->total_item : 0;
        $updateSale->total_qty = $request->total_qty ? $request->total_qty : 0;
        $updateSale->total_sold_qty = $request->status == SaleStatus::Final->value ? $request->total_qty : $updateSale->total_sold_qty;
        $updateSale->total_quotation_qty = $request->status == SaleStatus::Quotation->value ? $request->total_qty : $updateSale->total_quotation_qty;
        $updateSale->total_quotation_qty = $updateSale->quotation_status == BooleanType::True->value ? $request->total_qty : $updateSale->total_quotation_qty;
        $updateSale->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $updateSale->order_discount_type = $request->order_discount_type;
        $updateSale->order_discount = $request->order_discount ? $request->order_discount : 0;
        $updateSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $updateSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $updateSale->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0;
        $updateSale->total_invoice_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $updateSale->save();

        return $updateSale;
    }

    public function printTemplateBySaleStatusForStore(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        $printPageSize = $request->print_page_size;
        if ($request->status == SaleStatus::Final->value) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount;

            return view('sales.print_templates.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.print_templates.draft_print', compact('draft', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Hold->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is hold.')]);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            return response()->json(['suspendedInvoiceMsg' => __('Invoice is suspended.')]);
        }
    }

    public function printTemplateBySaleStatusForUpdate(object $request, object $sale, object $customerCopySaleProducts): mixed
    {
        $printPageSize = $request->print_page_size;
        if ($request->status == SaleStatus::Final->value) {

            $changeAmount = $request->change_amount > 0 ? $request->change_amount : 0;
            $receivedAmount = $request->received_amount;
            return view('sales.print_templates.sale_print', compact('sale', 'receivedAmount', 'changeAmount', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Draft->value) {

            $draft = $sale;
            return view('sales.print_templates.draft_print', compact('draft', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Quotation->value) {

            $quotation = $sale;
            return view('sales.print_templates.quotation_print', compact('quotation', 'customerCopySaleProducts', 'printPageSize'));
        } elseif ($request->status == SaleStatus::Hold->value) {

            return response()->json(['holdInvoiceMsg' => __('Invoice is hold.')]);
        } elseif ($request->status == SaleStatus::Suspended->value) {

            return response()->json(['suspendedInvoiceMsg' => __('Invoice is suspended.')]);
        }
    }
}
