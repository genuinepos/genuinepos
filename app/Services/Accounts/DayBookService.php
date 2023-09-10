<?php

namespace App\Services\Accounts;

use App\Models\Accounts\DayBook;

class DayBookService
{
    public static function voucherTypes()
    {
        return  [
            1 => 'Sales',
            2 => 'Sales Order',
            3 => 'Sales Return',
            4 => 'Purchase',
            5 => 'Purchase Order',
            6 => 'Purchase Return',
            7 => 'Stock Adjustment',
            8 => 'Receipt',
            9 => 'Payment',
            10 => 'Contra',
            11 => 'Expenses',
            12 => 'Incomes',
        ];
    }

    public function voucherType($voucherTypeId)
    {
        $data = [
            1 => ['name' => 'Sales', 'id' => 'sale_id', 'voucher_no' => 'sales_voucher', 'details_id' => 'sale_id', 'link' => 'sales.show'],
            2 => ['name' => 'Sales Order', 'id' => 'sale_id', 'voucher_no' => 'sales_order_voucher', 'details_id' => 'sale_id', 'link' => 'sales.order.show'],
            3 => ['name' => 'Sales Return', 'id' => 'sale_return_id', 'voucher_no' => 'sale_return_voucher', 'details_id' => 'sale_return_id', 'link' => 'sales.returns.show'],
            4 => ['name' => 'Purchase', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show'],
            5 => ['name' => 'Purchase Order', 'id' => 'purchase_id', 'voucher_no' => 'purchase_voucher', 'details_id' => 'purchase_id', 'link' => 'purchases.show.order'],
            6 => ['name' => 'Purchase Return', 'id' => 'purchase_return_id', 'voucher_no' => 'purchase_return_voucher', 'details_id' => 'purchase_return_id', 'link' => 'purchases.returns.show'],
            7 => ['name' => 'Stock Adjustment', 'id' => 'stock_adjustment_id', 'voucher_no' => 'stock_adjustment_voucher', 'details_id' => 'stock_adjustment_id', 'link' => 'stock.adjustments.show'],
            // 7 => ['name' => 'Receipt', 'id' => 'payment_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.receipts.show'],
            // 9 => ['name' => 'Payment', 'id' => 'payment_id', 'voucher_no' => 'payment_voucher', 'details_id' => 'payment_id', 'link' => 'vouchers.payments.show'],
            // 10 => ['name' => 'Contra', 'id' => 'contra_id', 'voucher_no' => 'contra_voucher', 'details_id' => 'contra_id', 'link' => 'vouchers.contras.show'],
            //  11 => ['name' => 'Expenses', 'id' => 'expense_id', 'voucher_no' => 'expense_voucher', 'details_id' => 'expense_id', 'link' => 'vouchers.expenses.show'],
            //  12 => ['name' => 'Incomes', 'id' => 'expense_id', 'voucher_no' => 'expense_voucher', 'details_id' => 'expense_id', 'link' => 'vouchers.expenses.show'],
        ];

        return $data[$voucherTypeId];
    }

    public function addDayBook(
        $voucherTypeId,
        $date,
        $accountId,
        $transId,
        $amount,
        $amountType,
        $productId = null,
    ) {
        $voucherType = $this->voucherType($voucherTypeId);
        $add = new DayBook();
        $add->date_ts = date('Y-m-d H:i:s', strtotime($date . date(' H:i:s')));
        $add->account_id = $accountId ? $accountId : null;
        $add->voucher_type = $voucherTypeId;
        $add->{$voucherType['id']} = $transId;
        $add->amount = $amount;
        $add->amount_type = $amountType;
        $add->save();
    }

    public function updateDayBook(
        $voucherTypeId,
        $date,
        $accountId,
        $transId,
        $amount,
        $amountType,
        $productId = null,
    ) {
        $voucherType = $this->voucherType($voucherTypeId);
        $update = '';
        $query = DayBook::where($voucherType['id'], $transId)->where('voucher_type', $voucherTypeId);
        $update = $query->first();

        if ($update) {

            $previousTime = date(' H:i:s', strtotime($update->date));
            $update->date_ts = date('Y-m-d H:i:s', strtotime($date . $previousTime));
            $update->account_id = $accountId ? $accountId : null;
            $update->amount = $amount;
            $update->amount_type = $amountType;
            $update->save();
        } else {

            $this->addDayBook(
                $voucherTypeId,
                $date,
                $accountId,
                $transId,
                $amount,
                $amountType,
                $productId,
            );
        }
    }

    public function particulars($request, $voucherType, $daybook)
    {
        if ($voucherType == 1) {
            return $this->salesDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 2) {
            return $this->salesDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 3) {
            return $this->salesReturnDetails($request, $daybook);
        } elseif ($voucherType == 4) {
            return $this->purchaseDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 5) {
            return $this->purchaseDetails($request, $voucherType, $daybook);
        } elseif ($voucherType == 6) {
            return $this->purchaseReturnDetails($request, $daybook);
        } elseif ($voucherType == 8) {
            return $this->stockAdjustmentDetails($request, $daybook);
        }
    }

    public function salesDetails($request, $voucherType, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $daybook?->sale?->sale_note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table" style="heigh:0px!important;">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('menu.as_per_details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';
            $totalQty = $voucherType == 1 ? $daybook?->sale?->total_sold_qty : $daybook?->sale?->total_ordered_qty;
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($totalQty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 1 ? __('menu.sale_discount') : __('menu.order_discount')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 1 ? __('menu.sale_tax') : __('menu.order_tax')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->order_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 1 ? __('menu.total_invoice_amount') : __('menu.total_ordered_amount')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->sale?->total_payable_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.payment_note') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . $daybook?->sale?->payment_note . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->sale->saleProducts as $saleProduct) {

                if ($saleProduct->quantity > 0 || $saleProduct->ordered_quantity > 0) {

                    $variantName = $saleProduct->variant ? ' - ' . $saleProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $saleProduct?->product?->name . $variantName . '</td>';

                    $baseUnitMultiplier = $saleProduct?->saleUnit?->base_unit_multiplier ? $saleProduct?->saleUnit?->base_unit_multiplier : 1;
                    $soldQty = $saleProduct->quantity / $baseUnitMultiplier;
                    $orderedQty = $saleProduct->ordered_quantity / $baseUnitMultiplier;
                    $showingQty = $soldQty > 0 ? $soldQty : $orderedQty;
                    $priceIncTax = $saleProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($showingQty) . '/' . $saleProduct?->saleUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($showingQty) . 'X' . $priceIncTax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($saleProduct->subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }
            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? '<a href="' . route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']) . '" target="_blank">' . $showingAccount . '</a>' : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>' . $accountName . $productName . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function salesReturnDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $daybook?->salesReturn?->return_note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('menu.as_per_details') . ')' . ':</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.net_total_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.return_discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.return_tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_returned_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->salesReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->salesReturn->returnProducts as $returnProduct) {

                if ($returnProduct->return_qty) {

                    $variantName = $returnProduct?->variant ? ' - ' . $returnProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $returnProduct?->product?->name . $variantName . '</td>';

                    $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                    $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                    $unitPriceIncTax = $returnProduct->unit_price_inc_tax * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($returnedQty) . '/' . $returnProduct?->returnUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($returnedQty) . 'X' . $unitPriceIncTax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? '<a href="' . route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']) . '" target="_blank">' . $showingAccount . '</a>' : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>' . $accountName . $productName . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseDetails($request, $voucherType, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $daybook?->purchase?->purchase_note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100">';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1.2 !important;"><strong>(' . __('menu.as_per_details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.net_total_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 4 ? __('menu.purchase_discount') : __('menu.order_discount')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->order_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 4 ? __('menu.purchase_tax') : __('menu.order_tax')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->purchase_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . ($voucherType == 4 ? __('menu.total_invoice_amount') : __('menu.total_ordered_amount')) . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchase?->total_purchase_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.payment_note') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . $daybook?->purchase?->payment_note . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            if (count($daybook?->purchase?->purchaseProducts) > 0) {

                foreach ($daybook->purchase->purchaseProducts as $purchaseProduct) {

                    $variantName = $purchaseProduct?->variant ? ' - ' . $purchaseProduct->variant->name : '';
                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $purchaseProduct?->product?->name . $variantName . '</td>';

                    $baseUnitMultiplier = $purchaseProduct?->purchaseUnit?->base_unit_multiplier ? $purchaseProduct?->purchaseUnit?->base_unit_multiplier : 1;
                    $purchasedQty = $purchaseProduct->quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $purchaseProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($purchasedQty) . '/' . $purchaseProduct?->purchaseUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($purchasedQty) . 'X' . $unitCostIncTax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($purchaseProduct->line_total) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            } elseif (count($daybook->purchase->orderedProducts) > 0) {

                foreach ($daybook->purchase->orderedProducts as $orderProduct) {

                    $inventoryDetails .= '<tr>';
                    $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $orderProduct?->product?->name . '</td>';

                    $baseUnitMultiplier = $orderProduct?->orderUnit?->base_unit_multiplier ? $orderProduct?->orderUnit?->base_unit_multiplier : 1;
                    $orderedQty = $orderProduct->order_quantity / $baseUnitMultiplier;
                    $unitCostIncTax = $orderProduct->net_unit_cost * $baseUnitMultiplier;

                    $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($orderedQty) . '/' . $orderProduct?->orderUnit?->code_name . '</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($orderedQty) . 'X' . $unitCostIncTax . ')</td>';

                    $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($orderProduct->line_total) . '</td>';
                    $inventoryDetails .= '</tr>';
                }
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? '<a href="' . route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']) . '" target="_blank">' . $showingAccount . '</a>' : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>' . $accountName . $productName . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function purchaseReturnDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $daybook?->purchaseReturn?->note . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('menu.as_per_details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.net_total_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.return_discount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_discount_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.return_tax') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->return_tax_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_returned_amount') . '.</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->purchaseReturn?->total_return_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($daybook->purchaseReturn->returnProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->purchaseReturn->returnProducts as $returnProduct) {

                $variantName = $returnProduct?->variant ? ' - ' . $returnProduct->variant->name : '';
                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1!important;" class="w-50">- ' . $returnProduct?->product?->name . $variantName . '</td>';

                $baseUnitMultiplier = $returnProduct?->returnUnit?->base_unit_multiplier ? $returnProduct?->returnUnit?->base_unit_multiplier : 1;
                $returnedQty = $returnProduct->return_qty / $baseUnitMultiplier;
                $unitCostIncTax = $returnProduct->unit_cost_inc_tax * $baseUnitMultiplier;

                $inventoryDetails .= '<td style="line-height:1!important;">' . \App\Utils\Converter::format_in_bdt($returnedQty) . '/' . $returnProduct?->returnUnit?->code_name . '</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">(' . \App\Utils\Converter::format_in_bdt($returnedQty) . 'X' . $unitCostIncTax . ')</td>';

                $inventoryDetails .= '<td style="line-height:1!important;">=' . \App\Utils\Converter::format_in_bdt($returnProduct->return_subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? '<a href="' . route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']) . '" target="_blank">' . $showingAccount . '</a>' : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>' . $accountName . $productName . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }

    public function stockAdjustmentDetails($request, $daybook)
    {
        $showingAccountId = $daybook->account ? $daybook?->account?->id : '';
        $showingAccount = $daybook->account ? $daybook?->account?->name : '';
        $showingProduct = $daybook->product ? $daybook?->product?->name : '';

        $note = '';
        if ($request->note == 1) {

            $note = '<p class="m-0 p-0">' . $daybook?->stockAdjustment?->reason . '</p>';
        }

        $voucherDetails = '';
        if ($request->voucher_details == 1) {

            $voucherDetails .= '<table class="w-100 td_child_table">';
            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;"><strong>(' . __('menu.as_per_details') . ')' . ' :</strong></td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_qty') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->total_qty) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.net_total_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->net_total_amount) . '</td>';
            $voucherDetails .= '</tr>';

            $voucherDetails .= '<tr>';
            $voucherDetails .= '<td style="line-height:1 !important;" class="w-60"><strong>' . __('menu.total_recovered_amount') . '</strong></td>';
            $voucherDetails .= '<td style="line-height:1 !important;"> : ' . \App\Utils\Converter::format_in_bdt($daybook?->stockAdjustment?->recovered_amount) . '</td>';
            $voucherDetails .= '</tr>';
            $voucherDetails .= '</table>';
        }

        $inventoryDetails = '';
        if ($request->inventory_list == 1 && isset($daybook->stockAdjustment->adjustmentProducts)) {

            $inventoryDetails .= '<table class="w-100 td_child_table">';
            foreach ($daybook->stockAdjustment->adjustmentProducts as $adjustmentProduct) {

                $variantName = $adjustmentProduct?->variant ? ' - ' . $adjustmentProduct->variant->name : '';

                $inventoryDetails .= '<tr>';
                $inventoryDetails .= '<td style="line-height:1 !important;" class="w-50">- ' . $adjustmentProduct?->product?->name . $variantName . '</td>';
                $inventoryDetails .= '<td style="line-height:1 !important;">' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . '/' . $adjustmentProduct->unit . '</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">(' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->quantity) . 'X' . $adjustmentProduct->unit_cost_inc_tax . ')</td>';

                $inventoryDetails .= '<td style="line-height:1 !important;">=' . \App\Utils\Converter::format_in_bdt($adjustmentProduct->subtotal) . '</td>';
                $inventoryDetails .= '</tr>';
            }

            $inventoryDetails .= '</table>';
        }

        $accountName = $showingAccountId ? '<a href="' . route('accounting.accounts.ledger', [($showingAccountId ? $showingAccountId : 'null'), 'accountId']) . '" target="_blank">' . $showingAccount . '</a>' : '';
        $productName = $showingProduct ? $showingProduct : '';

        return '<p class="m-0 p-0"><strong>' . $accountName . $productName . '</strong></p>' . $voucherDetails . $inventoryDetails . $note;
    }
}
