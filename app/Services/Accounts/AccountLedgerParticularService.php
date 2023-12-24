<?php

namespace App\Services\Accounts;

use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;

class AccountLedgerParticularService
{
    public function particulars($request, $voucherType, $ledger)
    {
        if ($voucherType == 0) {
            return $this->openingBalanceDetails($request, $ledger);
        } elseif ($voucherType == 1) {
            return $this->salesDetails($request, $ledger);
        } elseif ($voucherType == 2) {
            return $this->salesReturnDetails($request, $ledger);
        } elseif ($voucherType == 3) {
            return $this->purchaseDetails($request, $ledger);
        } elseif ($voucherType == 4) {
            return $this->purchaseReturnDetails($request, $ledger);
        } elseif ($voucherType == 5) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 7) {
            return $this->stockAdjustmentDetails($request, $ledger);
        } elseif ($voucherType == 8) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 9) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 12) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 13) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 15) {
            return $this->accountingVoucherDetails($request, $ledger);
        } elseif ($voucherType == 16) {
            return $this->saleProductTaxAndExchange($request, $ledger);
        } elseif ($voucherType == 17) {
            return $this->purchaseProductTax($request, $ledger);
        } elseif ($voucherType == 18) {
            return $this->saleReturnProductTax($request, $ledger);
        } elseif ($voucherType == 19) {
            return $this->purchaseReturnProductTax($request, $ledger);
        } elseif ($voucherType == 20) {
            return $this->saleProductTaxAndExchange($request, $ledger);
        } elseif ($voucherType == 21) {
            return $this->accountingVoucherDetails($request, $ledger);
        }
    }

    public function openingBalanceDetails($request, $ledger)
    {
        $particulars = '<p class="m-0 p-0">';
        $particulars .= '<strong>' . __('Opening Balance') . '</strong>';
        $particulars .= '-' . $this->ledgerBranchName($ledger);

        return $particulars;
    }

    public function salesDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->sale?->customer_account_id ? $ledger?->sale?->salesAccount?->name : $ledger?->sale?->customer?->name;
        $showingAccountId = $ledger->account_id == $ledger?->sale?->customer_account_id ? $ledger?->sale?->salesAccount?->id : $ledger?->sale?->customer?->id;

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $ledger?->sale?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->sale?->total_sold_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Sale Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1.2 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->sale?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Sale Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->sale?->order_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->sale?->total_invoice_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $saleProduct?->product?->name . '</td>';
                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . '/' . $saleProduct?->unit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . 'X' . $saleProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($saleProduct->subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $ledgerReferenceBranch . '</p><p class="m-0 p-0"><strong><a href="' . route('accounts.ledger.index', [$showingAccountId]) . '" target="_blank">' . $showingAccount . '</a></strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function salesReturnDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->salesReturn?->customer_account_id ? $ledger?->salesReturn?->salesAccount?->name : $ledger?->salesReturn?->customer?->name;
        $showingAccountId = $ledger->account_id == $ledger?->salesReturn?->customer_account_id ? $ledger?->salesReturn?->salesAccount?->id : $ledger?->salesReturn?->customer?->id;

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $ledger?->salesReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            // $voucherDetails .= '<tr>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>Customer</strong></td>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"> : ' . $ledger?->sale?->customer?->name . '</td>';
            // $voucherDetails .= '</tr>';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->salesReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->salesReturn->saleReturnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->unit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p>' . $ledgerReferenceBranch . '</p><p class="m-0 p-0"><strong><a href="' . route('accounts.ledger.index', [$showingAccountId]) . '" target="_blank">' . $showingAccount . '</a></strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->purchase?->supplier_account_id ? $ledger?->purchase?->purchaseAccount?->name : $ledger?->purchase?->supplier?->name;
        $showingAccountId = $ledger->account_id == $ledger?->purchase?->supplier_account_id ? $ledger?->purchase?->purchaseAccount?->id : $ledger?->purchase?->supplier?->id;

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $ledger->purchase->purchase_note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchase?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchase?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Purchase Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchase?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Purchase Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchase?->purchase_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchase?->total_purchase_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->purchase->purchaseProducts as $purchaseProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $purchaseProduct?->product?->name . '</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . '/' . $purchaseProduct->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . 'X' . $purchaseProduct->net_unit_cost . ')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $ledgerReferenceBranch . '</p><p class="m-0 p-0"><strong><a href="' . route('accounts.ledger.index', [$showingAccountId]) . '" target="_blank">' . $showingAccount . '</a></strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseReturnDetails($request, $ledger)
    {
        $showingAccount = $ledger->account_id == $ledger?->purchaseReturn?->supplier_account_id ? $ledger?->purchaseReturn?->purchaseAccount?->name : $ledger?->purchaseReturn?->supplier?->name;

        $showingAccountId = $ledger->account_id == $ledger?->purchaseReturn?->supplier_account_id ? $ledger?->purchaseReturn?->purchaseAccount?->id : $ledger?->purchaseReturn?->supplier?->id;

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $ledger?->purchaseReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->purchaseReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($ledger->purchaseReturn->purchaseReturnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->purchaseReturn->purchaseReturnProducts as $returnProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1!important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_cost_inc_tax . ')</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        return '<p>' . $ledgerReferenceBranch . '</p><p class="m-0 p-0"><strong><a href="' . route('accounts.ledger.index', [$showingAccountId]) . '" target="_blank">' . $showingAccount . '</a></strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function stockAdjustmentDetails($request, $ledger)
    {
        $showingAccount = '<a href="' . route('accounting.accounts.ledger', [($ledger?->stockAdjustment?->expenseAccount?->id)]) . '" target="_blank">' . $ledger?->expenseAccount?->expenseAccount?->name . '</a>';

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $ledger?->stockAdjustment?->reason . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->stockAdjustment) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Recovered Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($ledger?->stockAdjustment?->recovered_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($ledger->stockAdjustment->adjustmentProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($ledger->stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important;" class="w-60">- ' . $adjustmentProduct?->product?->name . '</td>';
                $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . '/' . $adjustmentProduct?->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . 'X' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax) . ')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>' . $showingAccount . '</strong>' . $ledgerReferenceBranch . '</p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function accountingVoucherDetails($request, $ledger)
    {
        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $ledger?->voucherDescription?->accountingVoucher?->remarks . '</p>';
        }

        $ledgerReferenceBranch = $this->ledgerBranchName($ledger);

        $collection = $ledger->voucherDescription->accountingVoucher->voucherDescriptions;

        $descriptions = $collection->filter(function ($description, $key) use ($ledger) {

            return $description->id != $ledger->voucher_description_id;
        });

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $detailsAmountType = $ledger->amount_type == 'debit' ? ' Cr.' : ' Dr.';
            $voucherDetails .= '<p class="p-0 m-0">' . $ledgerReferenceBranch . '</p>';
            $voucherDetails .= '<p class="p-0 m-0"><strong>' . $detailsAmountType . ' (' . __('As Per Details') . ')' . ' :</strong></p>';
            $voucherDetails .= '<table class="w-100 td_child_table">';

            foreach ($descriptions as $description) {

                $transactionDetails = '';
                if ($request->transaction_details == BooleanType::True->value) {

                    if (
                        $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                    ) {

                        $transactionDetails .= $description?->paymentMethod?->name;
                        $transactionDetails .= '-TransNo:' . $description->transaction_no;
                        $transactionDetails .= '-ChequeNo: ' . $description->cheque_no;
                        $transactionDetails .= '-SerialNo: ' . $description->cheque_serial_no;
                        $transactionDetails .= '-IssueDate: ' . $description->cheque_issue_date;
                        // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
                    }
                }

                $amount = \App\Utils\Converter::format_in_bdt($description->amount);
                $amount_type = $description->amount_type == 'dr' ? ' <strong>Dr.</strong>' : ' <strong>Cr.</strong>';
                $__amount = ' : ' . $amount . $amount_type;

                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:1 !important;" class="w-60">' . '<strong><a href="' . route('accounts.ledger.index', [$description?->account?->id]) . '" target="_blank">' . $description?->account?->name . '</a></strong></td>';
                $voucherDetails .= '<td style="line-height:1 !important;">: ' . $amount . $amount_type . '</td>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:1 !important;">' . $transactionDetails . '</td></tr>';
                }

                $voucherDetails .= '</tr>';

                if (count($description->references) > 0) {

                    $referencesDetails = '';
                    // $referencesDetails = '<tr style="line-height:1 !important;"><td colspan="2" style="line-height:1 !important;"> </td></tr>';
                    $referencesDetails .= '<tr><td colspan="2" style="line-height:1 !important;"><strong>(' . __('Against References') . '):</strong>';
                    foreach ($description->references as $reference) {

                        $sale = '';
                        if ($reference->sale) {

                            if ($reference?->sale?->order_status == BooleanType::True->value) {

                                $sale = '<p class="fw-bold" style="line-height:14px">' . __('Sales-Order') . ' : <a href="' . route('sale.orders.show', $reference->sale_id) . '" id="details_btn">' . $reference?->sale?->order_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            } else {

                                $sale = '<p class="fw-bold" style="line-height:14px">' . __('Sales') . ' : <a href="' . route('sales.show', $reference->sale_id) . '" id="details_btn">' . $reference?->sale?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            }
                        }

                        $salesReturn = '';
                        if ($reference->salesReturn) {

                            $sale = '<p class="fw-bold" style="line-height:14px">' . __('Sales-Return') . ' : <a href="' . route('sales.returns.show', $reference->sale_return_id) . '" id="details_btn">' . $reference?->salesReturn?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                        }

                        $purchase = '';
                        if ($reference->purchase) {

                            if ($reference->purchase->purchase_status == PurchaseStatus::Purchase->value) {

                                $purchase = '<p class="fw-bold" style="line-height:14px">' . __('Purchase') . ' : <a href="' . route('purchases.show', $reference->purchase_id) . '" id="details_btn">' . $reference?->purchase?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            } else {

                                $purchase = '<p class="fw-bold" style="line-height:14px">' . __('P/o') . ' : <a href="' . route('purchase.orders.show', $reference->purchase_id) . '" id="details_btn">' . $reference?->purchase?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            }
                        }

                        $purchaseReturn = '';
                        if ($reference->purchaseReturn) {

                            $sale = '<p class="fw-bold" style="line-height:14px">' . __('Purchase-Return') . ' : <a href="' . route('purchase.returns.show', $reference->purchase_return_id) . '" id="details_btn">' . $reference?->purchaseReturn?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                        }

                        $stockAdjustment = '';
                        if ($reference->stockAdjustment) {

                            $stockAdjustment = '<p class="fw-bold" style="line-height:14px">' . __('Stock Adjustment') . ' : <a href="' . route('stock.adjustments.show', $reference->stock_adjustment_id) . '" id="details_btn">' . $reference?->stockAdjustment->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount);
                        }

                        $payroll = '';
                        if ($reference->payroll) {

                            $payroll = '<p class="fw-bold" style="line-height:14px">' . __('Payroll') . ' : <a href="' . route('hrm.payrolls.show', $reference->payroll_id) . '" id="details_btn">' . $reference?->payroll?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount);
                        }

                        $referencesDetails .= $sale . $salesReturn . $purchase . $purchaseReturn . $stockAdjustment . $payroll;
                    }

                    $referencesDetails .= '</td></tr>';
                    $voucherDetails .= $referencesDetails;
                }
            }

            $voucherDetails .= '</table>';
        } else {

            // $description = $descriptions->first();

            $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
            });

            $filteredNotCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                return $description?->account?->group->sub_sub_group_number != 1 && $description?->account?->group->sub_sub_group_number != 2 && $description?->account?->group->sub_sub_group_number != 11;
            });

            $description = '';
            $ledgerAccountGroup = $ledger?->account?->group;
            if (
                $ledgerAccountGroup &&
                $ledgerAccountGroup->sub_sub_group_number != 1 &&
                $ledgerAccountGroup->sub_sub_group_number != 2 &&
                $ledgerAccountGroup->sub_sub_group_number != 11
            ) {

                $description = count($filteredCashOrBankAccounts) > 0 ? $filteredCashOrBankAccounts->first() : $descriptions->first();
            } else {

                $description = count($filteredNotCashOrBankAccounts) > 0 ? $filteredNotCashOrBankAccounts->first() : $descriptions->first();
            }

            $transactionDetails = '';
            $transactionDetails = '';

            if ($request->transaction_details == BooleanType::True->value) {

                if (
                    $description->payment_method_id || $description->transaction_no || $description->cheque_no || $description->cheque_serial_no || $description->cheque_issue_date
                ) {

                    $transactionDetails .= $description?->paymentMethod?->name;
                    $transactionDetails .= ' - TransNo: ' . $description->transaction_no;
                    $transactionDetails .= ' - ChequeNo: ' . $description->cheque_no;
                    $transactionDetails .= ' - SerialNo: ' . $description->cheque_serial_no;
                    $transactionDetails .= ' - IssueDate: ' . $description->cheque_issue_date;
                    // $transactionDetails .= ' - R.Note : ' . $description->remarkable_note;
                }
            }

            $voucherDetails .= '<p>' . $ledgerReferenceBranch . '</p><p><strong><a href="' . route('accounts.ledger.index', [($description?->account?->id ? $description?->account?->id : 'null')]) . '" target="_blank">' . $description?->account?->name . '</a></strong></p>' . ($transactionDetails ? '<p class="p-0 m-0">' . $transactionDetails . '</p>' : '');
        }

        return $voucherDetails . $note;
    }

    public function saleProductTaxAndExchange($request, $ledger)
    {
        $saleProduct = $ledger?->saleProduct;

        $showingAccount = $ledger->account_id == $saleProduct?->sale?->customer_account_id ? $saleProduct?->sale?->salesAccount?->name : $saleProduct?->sale?->customer?->name;
        $assignedBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $saleProduct?->sale?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:11px !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($saleProduct?->sale?->total_sold_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Sale Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($saleProduct?->sale?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Sale Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"> : ' . \App\Utils\Converter::format_in_bdt($saleProduct?->sale?->order_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($saleProduct?->sale?->total_invoice_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($saleProduct?->sale?->saleProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($saleProduct->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $saleProduct?->product?->name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . '/' . $saleProduct->unit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_percent) . '%=' . \App\Utils\Converter::format_in_bdt($saleProduct->unit_tax_amount * $baseUnitMultiplier) . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . 'X' . $saleProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($saleProduct->subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>' . $showingAccount . '</strong>' . $assignedBranch . '</p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseProductTax($request, $ledger)
    {
        $purchaseProduct = $ledger?->purchaseProduct;

        $showingAccount = $ledger->account_id == $purchaseProduct?->purchase?->supplier_account_id ? $purchaseProduct?->purchase?->purchaseAccount?->name : $purchaseProduct?->purchase?->supplier?->name;
        $assignedBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $purchaseProduct?->purchase?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Purchased Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Purchase Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->purchase_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseProduct?->purchase?->total_purchase_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($purchaseProduct?->purchase?->purchaseProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($purchaseProduct->purchase->purchaseProducts as $purchaseProduct) {

                if ($purchaseProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $purchaseProduct?->product?->name . '</td>';

                    $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                    $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $purchaseProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . '/' . $purchaseProduct?->purchaseUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($purchaseProduct->unit_tax_percent) . '%=' . \App\Utils\Converter::format_in_bdt($purchaseProduct->unit_tax_amount * $baseUnitMultiplier) . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . 'X' . $purchaseProduct->net_unit_cost . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>' . $showingAccount . '</strong>' . $assignedBranch . '</p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function saleReturnProductTax($request, $ledger)
    {
        $salesReturnProduct = $ledger?->salesReturnProduct;
        $showingAccount = $ledger->account_id == $salesReturnProduct?->saleReturn?->customer_account_id ? $salesReturnProduct?->salesReturn?->salesAccount?->name : $salesReturnProduct?->salesReturn?->customer?->name;

        $assignedBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $salesReturnProduct?->salesReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . ' (' . __('menu.as_base_unit') . ')' . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($salesReturnProduct?->salesReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($salesReturnProduct->salesReturn->saleReturnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($salesReturnProduct->salesReturn->saleReturnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->returnUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_percent) . '%=' . \App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_amount * $baseUnitMultiplier) . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>' . $showingAccount . '</strong>' . $assignedBranch . '</p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseReturnProductTax($request, $ledger)
    {
        $purchaseReturnProduct = $ledger?->purchaseReturnProduct;
        $showingAccount = $ledger->account_id == $purchaseReturnProduct?->purchaseReturn?->supplier_account_id ? $purchaseReturnProduct?->purchaseReturn?->purchaseAccount?->name : $purchaseReturnProduct?->purchaseReturn?->supplier?->name;

        $assignedBranch = $this->ledgerBranchName($ledger);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $purchaseReturnProduct?->purchaseReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($purchaseReturnProduct?->purchaseReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($purchaseReturnProduct->purchaseReturn->purchaseReturnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($purchaseReturnProduct->purchaseReturn->purchaseReturnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->returnUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_percent) . '%=' . \App\Utils\Converter::format_in_bdt($returnProduct->unit_tax_amount * $baseUnitMultiplier) . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_cost_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p class="m-0 p-0"><strong>' . $showingAccount . '</strong>' . $assignedBranch . '</p>' . $voucherDetails . $inventoryDetails . $note;
    }

    private function ledgerBranchName($ledger)
    {
        $generalSettings = config('generalSettings');
        if ($ledger->branch) {

            $areaName = $ledger?->branch?->area_name ? '(' . $ledger?->branch?->area_name . ')' : '';
            $branchCode = $ledger?->branch?->branch_code ? '-' . $ledger?->branch?->branch_code : '';

            if ($ledger?->branch?->parentBranch) {

                return $ledger?->branch?->parentBranch?->name . $areaName . '-' . $branchCode;
            } else {

                return $ledger?->branch?->name . $areaName . '-' . $branchCode;
            }
        } else {

            return $generalSettings['business__business_name'] . '(' . __('Business') . ')';
        }
    }
}
