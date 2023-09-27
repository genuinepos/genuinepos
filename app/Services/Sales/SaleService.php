<?php

namespace App\Services\Sales;

use App\Models\Sales\Sale;
use App\Enums\SaleStatus;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function addSale(object $request, int $saleScreenType, object $codeGenerator, ?string $invoicePrefix): object
    {
        $invoiceId = $codeGenerator->generateMonthAndTypeWise(table: 'sales', column: 'invoice_id', typeColName: 'status', typeValue: SaleStatus::tryFrom($request->status)->value, prefix: $invoicePrefix, splitter: '-', suffixSeparator: '-', branchId: auth()->user()->branch_id);

        $addSale = new Sale();
        $addSale->invoice_id = $invoiceId;
        $addSale->created_by_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->branch_id = auth()->user()->branch_id;
        $addSale->customer_account_id = $request->customer_account_id;
        $addSale->status = $request->status;
        $addSale->pay_term = $request->pay_term;
        $addSale->pay_term_number = $request->pay_term_number;
        $addSale->date = $request->date;
        $addSale->date_ts = date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s')));
        $addSale->sale_date_ts = $request->status == SaleStatus::Final->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->quotation_date_ts = $request->status == SaleStatus::Quotation->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->order_date_ts = $request->status == SaleStatus::Order->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->order_date_ts = $request->status == SaleStatus::Draft->value ? date('Y-m-d H:i:s', strtotime($request->date . date(' H:i:s'))) : null;
        $addSale->quotation_status = $request->status == SaleStatus::Quotation->value ? SaleStatus::Quotation->value : 0;
        $addSale->order_status = $request->status == SaleStatus::Order->value ? SaleStatus::Order->value : 0;
        $addSale->draft_status = $request->status == SaleStatus::Draft->value ? SaleStatus::Draft->value : 0;
        $addSale->total_item = $request->total_item;
        $addSale->total_qty = $request->total_qty;
        $addSale->total_sold_qty = SaleStatus::Final->value ? $request->total_qty : 0;
        $addSale->total_ordered_qty = SaleStatus::Order->value ? $request->total_qty : 0;
        $addSale->total_quotation_qty = SaleStatus::Quotation->value ? $request->total_qty : 0;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount;
        $addSale->order_discount_amount = $request->order_discount_amount;
        $addSale->sale_tax_ac_id = $request->sale_tax_ac_id;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $addSale->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $addSale->shipment_details = $request->shipment_details;
        $addSale->shipment_address = $request->shipment_address;
        $addSale->shipment_status = $request->shipment_status;
        $addSale->delivered_to = $request->delivered_to;
        $addSale->note = $request->sale_note;
        $addSale->change_amount = $request->change_amount > 0 ? $request->change_amount : 0.00;
        $addSale->total_invoice_amount = $request->total_invoice_amount;
        $addSale->due = $request->total_invoice_amount;
        $addSale->save();

        return $addSale;
    }

    public function adjustSaleInvoiceAmounts($sale)
    {
        $totalSaleReceived = DB::table('voucher_description_references')
            ->where('voucher_description_references.sale_id', $sale->id)
            ->select(DB::raw('sum(voucher_description_references.amount) as total_received'))
            ->groupBy('voucher_description_references.sale_id')
            ->get();

        $totalReturn = DB::table('sale_returns')
            ->where('sale_returns.sale_id', $sale->id)
            ->select(DB::raw('sum(total_return_amount) as total_returned_amount'))
            ->groupBy('sale_returns.sale_id')
            ->get();

        $due = $sale->total_payable_amount
            - $totalSaleReceived->sum('total_received')
            - $totalReturn->sum('total_returned_amount');

        $sale->paid = $totalPurchasePaid->sum('total_received');
        $sale->due = $due;
        $sale->sale_return_amount = $totalReturn->sum('total_returned_amount');
        $sale->save();

        return $sale;
    }

    public function restrictions(object $request, object $accountService): ?array
    {
        if (($request->status == SaleStatus::Order->value || $request->status == SaleStatus::Quotation->value) && !$request->customer_account_id) {

            return ['pass' => false, 'msg' => __('Listed customer is required for sales order and quotation.')];
        }

        if ($request->status == SaleStatus::Final->value && $request->current_balance > 0) {

            $customerCreditLimit = DB::table('accounts')
                ->where('accounts.id', $request->customer_account_id)
                ->leftJoin('contacts', 'accounts.contact_id', 'contacts.id')
                ->select('customers.credit_limit')
                ->first();

            $creditLimit = $customerCreditLimit ? $customerCreditLimit->credit_limit : 0;
            $__credit_limit = $creditLimit ? $creditLimit : 0;
            $msg_1 = 'Customer does not have any credit limit.';
            $msg_2 = "Customer Credit Limit is ${__credit_limit}.";
            $__show_msg = $__credit_limit ? $msg_2 : $msg_1;

            if ($request->current_balance > $__credit_limit) {

                return ['pass' => false, 'msg' => $__show_msg];
            }
        }

        return ['pass' => true];
    }
}
