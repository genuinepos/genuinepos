<?php

namespace App\Services\Accounts\Reports\DayBook;

use App\Enums\BooleanType;
use App\Enums\PurchaseStatus;
use App\Enums\DayBookVoucherType;
use App\Enums\TransferStockReceiveStatus;

class DayBookPrintParticularService
{
    public function particulars(object $request, int $voucherType, object $daybook): string
    {
        if ($voucherType == DayBookVoucherType::Sales->value) {

            return $this->salesDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::SalesOrder->value) {

            return $this->salesDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::SalesReturn->value) {

            return $this->salesReturnDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Purchase->value) {

            return $this->purchaseDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::PurchaseOrder->value) {

            return $this->purchaseDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::PurchaseReturn->value) {

            return $this->purchaseReturnDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Receipt->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Payment->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Contra->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Expense->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Incomes->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::PayrollPayment->value) {

            return $this->accountingVoucherDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::StockAdjustment->value) {

            return $this->stockAdjustmentDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Payroll->value) {

            return $this->payrollDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::StockIssue->value) {

            return $this->stockIssueDetails(request: $request, daybook: $daybook);
        } elseif ($voucherType == DayBookVoucherType::Production->value) {

            return $this->productionDetails(request: $request, daybook: $daybook);
        }elseif ($voucherType == DayBookVoucherType::TransferStock->value) {

            return $this->transferStockDetails(request: $request, daybook: $daybook);
        }elseif ($voucherType == DayBookVoucherType::ReceivedStock->value) {

            return $this->transferStockDetails(request: $request, daybook: $daybook);
        }
    }

    public function salesDetails(object $request, object $daybook): string
    {
        $showingAccount = $daybook?->account?->name;
        $showingAccountId = $daybook->account_id;

        $daybookReferenceBranch = $this->dayBookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->sale?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $totalQty = $daybook->voucher_type == DayBookVoucherType::Sales->value ? $daybook?->sale?->total_sold_qty : $daybook?->sale?->total_ordered_qty;
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($totalQty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Sale Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Sale Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->order_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->total_invoice_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $saleProduct?->product?->name . '</td>';
                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . '/' . $saleProduct?->unit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($saleProduct->quantity) . 'X' . $saleProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($saleProduct->subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function salesReturnDetails(object $request, object $daybook): string
    {
        $showingAccount = $daybook?->account?->name;
        $showingAccountId = $daybook->account_id;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->salesReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            // $voucherDetails .= '<tr>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>Customer</strong></td>';
            // $voucherDetails .= '<td style="line-height:1.2 !important;"> : ' . $daybook?->sale?->customer?->name . '</td>';
            // $voucherDetails .= '</tr>';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Total') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->salesReturn->saleReturnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->unit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_price_inc_tax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseDetails(object $request, object $daybook): string
    {
        $showingAccount = $daybook?->account?->name;
        $showingAccountId = $daybook?->account_id;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook->purchase->purchase_note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $totalQty = $daybook->voucher_type == DayBookVoucherType::Purchase->value ? $daybook?->purchase?->total_qty : $daybook?->purchase?->po_qty;

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($totalQty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Purchase Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Purchase Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->purchase_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"class="w-60"><strong>' . __('Total Invoice Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->total_purchase_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->purchase->purchaseProducts as $purchaseProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $purchaseProduct?->product?->name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . '/' . $purchaseProduct->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($purchaseProduct->quantity) . 'X' . $purchaseProduct->net_unit_cost . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseReturnDetails(object $request, object $daybook): string
    {
        $showingAccount = $daybook?->account?->name;

        $showingAccountId = $daybook?->account_id;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->purchaseReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Return Discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Return Tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"><strong>' . __('Total Returned Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($daybook->purchaseReturn->purchaseReturnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->purchaseReturn->purchaseReturnProducts as $returnProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $returnProduct?->product?->name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . '/' . $returnProduct?->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($returnProduct->return_qty) . 'X' . $returnProduct->unit_cost_inc_tax . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function accountingVoucherDetails(object $request, object $daybook): string
    {
        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->voucherDescription?->accountingVoucher?->remarks . '</p>';
        }

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $collection = $daybook->voucherDescription->accountingVoucher->voucherDescriptions;

        $descriptions = $collection->filter(function ($description, $key) use ($daybook) {

            return $description->id != $daybook->voucher_description_id;
        });

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $detailsAmountType = $daybook->amount_type == 'debit' ? ' Cr.' : ' Dr.';
            $voucherDetails .= '<p class="p-0 m-0">' . $daybookReferenceBranch . '</p>';
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
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60">' . '<strong><a href="' . route('accounts.ledger.index', [$description?->account?->id]) . '" target="_blank">' . $description?->account?->name . '</a></strong></td>';
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;">: ' . $amount . $amount_type . '</td>';

                if ($transactionDetails) {

                    $voucherDetails .= '<tr><td colspan="2" style="line-height:12px !important;padding:0px!important;">' . $transactionDetails . '</td></tr>';
                }

                $voucherDetails .= '</tr>';

                if (count($description->references) > 0) {

                    $referencesDetails = '';
                    // $referencesDetails = '<tr style="line-height:1 !important;"><td colspan="2" style="line-height:1 !important;"> </td></tr>';
                    $referencesDetails .= '<tr><td colspan="2" style="line-height:12px !important;padding:0px!important;"><strong>(' . __('Against References') . '):</strong>';
                    foreach ($description->references as $reference) {

                        $sale = '';
                        if ($reference->sale) {

                            if ($reference?->sale?->order_status == BooleanType::True->value) {

                                $sale = '<p class="fw-bold" style="line-height:10px4px">' . __('Sales-Order') . ' : <a href="' . route('sale.orders.show', $reference->sale_id) . '" id="details_btn">' . $reference?->sale?->order_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            } else {

                                $sale = '<p class="fw-bold" style="line-height:10px4px">' . __('Sales') . ' : <a href="' . route('sales.show', $reference->sale_id) . '" id="details_btn">' . $reference?->sale?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            }
                        }

                        $salesReturn = '';
                        if ($reference->salesReturn) {

                            $sale = '<p class="fw-bold" style="line-height:10px4px">' . __('Sales-Return') . ' : <a href="' . route('sales.returns.show', $reference->sale_return_id) . '" id="details_btn">' . $reference?->salesReturn?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                        }

                        $purchase = '';
                        if ($reference->purchase) {

                            if ($reference->purchase->purchase_status == PurchaseStatus::Purchase->value) {

                                $purchase = '<p class="fw-bold" style="line-height:10px4px">' . __('Purchase') . ' : <a href="' . route('purchases.show', $reference->purchase_id) . '" id="details_btn">' . $reference?->purchase?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            } else {

                                $purchase = '<p class="fw-bold" style="line-height:10px4px">' . __('P/o') . ' : <a href="' . route('purchase.orders.show', $reference->purchase_id) . '" id="details_btn">' . $reference?->purchase?->invoice_id . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                            }
                        }

                        $purchaseReturn = '';
                        if ($reference->purchaseReturn) {

                            $sale = '<p class="fw-bold" style="line-height:10px4px">' . __('Purchase-Return') . ' : <a href="' . route('purchase.returns.show', $reference->purchase_return_id) . '" id="details_btn">' . $reference?->purchaseReturn?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount) . '<p>';
                        }

                        $stockAdjustment = '';
                        if ($reference->stockAdjustment) {

                            $stockAdjustment = '<p class="fw-bold" style="line-height:10px4px">' . __('Stock Adjustment') . ' : <a href="' . route('stock.adjustments.show', $reference->stock_adjustment_id) . '" id="details_btn">' . $reference?->stockAdjustment->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount);
                        }

                        $payroll = '';
                        if ($reference->payroll) {

                            $payroll = '<p class="fw-bold" style="line-height:10px4px">' . __('Payroll') . ' : <a href="' . route('hrm.payrolls.show', $reference->payroll_id) . '" id="details_btn">' . $reference?->payroll?->voucher_no . ' </a>= ' . \App\Utils\Converter::format_in_bdt($reference->amount);
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
            $daybookAccountGroup = $daybook?->account?->group;
            if (
                $daybookAccountGroup &&
                $daybookAccountGroup->sub_sub_group_number != 1 &&
                $daybookAccountGroup->sub_sub_group_number != 2 &&
                $daybookAccountGroup->sub_sub_group_number != 11
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

            $voucherDetails .= '<p>' . $daybookReferenceBranch . '</p><p><strong>' . $daybook?->account?->name . '</strong></p>' . ($transactionDetails ? '<p class="p-0 m-0">' . $transactionDetails . '</p>' : '');
        }

        return $voucherDetails . $note;
    }

    public function stockAdjustmentDetails(object $request, object $daybook): string
    {
        $showingAccount = '<a href="' . route('accounting.accounts.ledger', [($daybook?->account_id)]) . '" target="_blank">' . $daybook?->account?->name . '</a>';

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->stockAdjustment?->reason . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->stockAdjustment) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Recovered Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->recovered_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value && isset($daybook->stockAdjustment->adjustmentProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60">- ' . $adjustmentProduct?->product?->name . '</td>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . '/' . $adjustmentProduct?->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . 'X' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->unit_cost_inc_tax) . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function payrollDetails(object $request, object $daybook): string
    {
        $showingAccount = $daybook?->account?->name;
        $showingAccountId = $daybook->account_id;

        $daybookReferenceBranch = $this->dayBookBranchName($daybook);

        $note = '';

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Employee') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $daybook?->payroll?->user?->prefix . ' ' . $daybook?->payroll?->user?->name . $daybook?->payroll?->user?->last_name . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Month') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $daybook?->payroll?->month . '/' . $daybook?->payroll?->year . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Allowance') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->total_allowance) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Deduction') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->total_deduction) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Gross Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->gross_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Paid') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->paid) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Due') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->payroll?->due) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingAccount . '</strong></p>' . $voucherDetails;
    }

    public function stockIssueDetails(object $request, object $daybook): string
    {
        $variant = $daybook->variant ? '-' . $daybook?->variant?->variant_name : '';
        $product = $daybook->product ? $daybook?->product?->name : '';
        $showingProduct = $product . $variant;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $note = '<p class="m-0 p-0">' . $daybook?->stockIssue?->remarks . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Item') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockIssue?->total_item) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockIssue?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Total Amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockIssue?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->stockIssue->stockIssuedProducts as $issuedProduct) {

                $variantName = $issuedProduct?->variant ? ' - ' . $issuedProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $issuedProduct?->product?->name . $variantName . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($issuedProduct->quantity) . '/' . $issuedProduct?->unit->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($issuedProduct->quantity) . 'X' . $issuedProduct->unit_cost_inc_tax . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($issuedProduct->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingProduct . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function productionDetails(object $request, object $daybook): string
    {
        $variant = $daybook?->production?->variant ? '-' . $daybook?->production?->variant?->variant_name : '';
        $product = $daybook?->production?->product ? $daybook?->production?->product?->name : '';
        $showingProduct = $product . $variant;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Output Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->production?->total_output_quantity) . '/' . $daybook?->production?->unit?->code_name . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Wasted Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->production?->total_wasted_quantity) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Final Output Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->production?->total_final_output_quantity) . '/' . $daybook?->production?->unit?->code_name . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Additional Production Cost') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->production?->additional_production_cost) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Net Cost') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->production?->net_cost) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"><strong>(' . __('Ingredients') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';
            foreach ($daybook->production->ingredients as $ingredient) {

                $variantName = $ingredient?->variant ? ' - ' . $ingredient->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $ingredient?->product?->name . $variantName . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($ingredient->unit_tax_percent) . '=' . \App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($ingredient->final_qty) . 'X' . \App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($ingredient->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingProduct . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function transferStockDetails(object $request, object $daybook): string
    {
        $variant = $daybook->variant ? '-' . $daybook?->variant?->variant_name : '';
        $product = $daybook->product ? $daybook?->product?->name : '';
        $showingProduct = $product . $variant;

        $daybookReferenceBranch = $this->daybookBranchName($daybook);

        $note = '';
        if ($request->note == BooleanType::True->value) {

            $__note = $daybook->voucher_type == DayBookVoucherType::TransferStock->value ? $daybook?->transferStock?->transfer_note : $daybook?->transferStock?->receiver_note;
            $note = '<p class="m-0 p-0">' . $__note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == BooleanType::True->value) {

            $generalSettings = config('generalSettings');
            $senderBranch = null;
            $senderWarehouse = null;

            if ($daybook?->transferStock?->senderBranch) {

                if ($daybook?->transferStock?->senderBranch?->parentBranch) {

                    $senderBranch = $daybook?->transferStock?->senderBranch?->parentBranch->name . '(' . $daybook?->transferStock?->senderBranch?->area_name . ')';
                } else {

                    $senderBranch = $daybook?->transferStock?->senderBranch?->name . '(' . $daybook?->transferStock?->senderBranch?->area_name . ')';
                }
            } else {

                $senderBranch = $generalSettings['business_or_shop__business_name'];
            }

            if ($daybook?->transferStock?->senderWarehouse) {

                $senderWarehouse = $daybook?->transferStock?->senderWarehouse?->warehouse_name . '(' . $daybook?->transferStock?->senderWarehouse?->warehouse_code . ')';
            }

            $receiverBranch = null;
            $receiverWarehouse = null;

            if ($daybook?->transferStock?->receiverBranch) {

                if ($daybook?->transferStock?->receiverBranch?->parentBranch) {

                    $receiverBranch = $daybook?->transferStock?->receiverBranch?->parentBranch->name . '(' . $daybook?->transferStock?->receiverBranch?->area_name . ')';
                } else {

                    $receiverBranch = $daybook?->transferStock?->receiverBranch?->name . '(' . $daybook?->transferStock?->receiverBranch?->area_name . ')';
                }
            } else {

                $receiverBranch = $generalSettings['business_or_shop__business_name'];
            }

            if ($daybook?->transferStock?->receiverWarehouse) {

                $receiverWarehouse = $daybook?->transferStock?->receiverWarehouse->warehouse_name . '(' . $daybook?->transferStock?->receiverWarehouse?->warehouse_code . ')';
            }

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:16px !important;padding:0px!important;"><strong>(' . __('As Per Details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Send From') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $senderBranch . '</td>';
            $voucherDetails .= '</tr>';

            if (isset($senderWarehouse)) {
                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('At') . '</strong></td>';
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $senderWarehouse . '</td>';
                $voucherDetails .= '</tr>';
            }

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Receive From') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $receiverBranch . '</td>';
            $voucherDetails .= '</tr>';

            if (isset($receiverWarehouse)) {
                $voucherDetails .= '<tr>';
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('At') . '</strong></td>';
                $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . $receiverWarehouse . '</td>';
                $voucherDetails .= '</tr>';
            }

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Receive Status') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . TransferStockReceiveStatus::tryFrom($daybook?->transferStock?->receive_status)->name . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Send Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->transferStock?->total_send_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Stock Value') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->transferStock?->total_stock_value) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Received Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->transferStock?->total_received_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Total Received Qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->transferStock?->total_pending_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-60"><strong>' . __('Received Stock Value') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:12px !important;padding:0px!important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->transferStock?->received_stock_value) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == BooleanType::True->value) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->transferStock->transferStockProducts as $transferredProduct) {

                $variantName = $transferredProduct?->variant ? ' - ' . $issueProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;" class="w-50">- ' . $transferredProduct?->product?->name . $variantName . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">' . \App\Utils\Converter::format_in_bdt($transferredProduct->send_qty) . '/' . $transferredProduct?->unit->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">(' . \App\Utils\Converter::format_in_bdt($transferredProduct->send_qty) . 'X' . $transferredProduct->unit_cost_inc_tax . ')</td>';

                $inventoryDetails .= '<td style="line-height:12px !important;padding:0px!important;">=' . \App\Utils\Converter::format_in_bdt($transferredProduct->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }
            $inventoryDetails .= '</table>';
        }

        return '<p>' . $daybookReferenceBranch . '</p><p class="m-0 p-0"><strong>' . $showingProduct . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    private function dayBookBranchName(object $daybook): string
    {
        $generalSettings = config('generalSettings');
        if ($daybook->branch) {

            $areaName = $daybook?->branch?->area_name ? '(' . $daybook?->branch?->area_name . ')' : '';
            $branchCode = $daybook?->branch?->branch_code ? '-' . $daybook?->branch?->branch_code : '';

            if ($daybook?->branch?->parentBranch) {

                return $daybook?->branch?->parentBranch?->name . $areaName . $branchCode;
            } else {

                return $daybook?->branch?->name . $areaName . $branchCode;
            }
        } else {

            return $generalSettings['business_or_shop__business_name'] . '(' . __('Business') . ')';
        }
    }
}
