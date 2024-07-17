<?php

namespace App\Services\Accounts\Reports\VatTax;

use App\Enums\AccountLedgerVoucherType;

class OutputVatTaxParticularAndOnAmountService
{
    public function particulars($voucherType, $data)
    {
        if ($voucherType == AccountLedgerVoucherType::Sales->value) {
            return $this->salesDetails($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SaleProductTax->value || $voucherType == AccountLedgerVoucherType::Exchange->value) {
            return $this->saleProductTaxAndExchange($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseReturn->value) {
            return $this->purchaseReturnDetails($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseReturnProductTax->value) {
            return $this->purchaseReturnProductTax($data);
        }
    }

    public function onAmounts($voucherType, $data)
    {
        if ($voucherType == AccountLedgerVoucherType::Sales->value) {
            return $this->salesOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SaleProductTax->value || $voucherType == AccountLedgerVoucherType::Exchange->value) {
            return $this->saleProductOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseReturn->value) {
            return $this->purchaseReturnOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseReturnProductTax->value) {
            return $this->saleReturnProductOnAmount($data);
        }
    }

    public function salesDetails($data)
    {
        $customer = $data?->sale?->customer?->name;

        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">(' . __('Output Vat/Tax On Sales Invoice.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('Customer') . '</b>' . ' : ' . $customer . '</p>';
        return $html;
    }

    public function saleProductTaxAndExchange($data)
    {
        $saleProduct = $data?->saleProduct;

        $customer = $saleProduct?->sale?->customer?->name;
        $product = $saleProduct?->product?->name;
        $variant = $saleProduct?->variant ? ' - ' . $saleProduct?->variant?->variant_name : '';
        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 fw-bold mb-1 mt-1" style="line-height:1.3!important;">(' . __('Output Vat/Tax On Sold Product.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . __('Customer') . '</b>' . ' : ' . $customer . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('By') . '</b>' . ' : ' . $product . $variant . '</p>';
        return $html;
    }

    public function purchaseReturnDetails($data)
    {
        $supplier = $data?->purchaseReturn?->supplier?->name;

        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">(' . __('Output Vat/Tax By Purchase Return Voucher.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('Supplier') . '</b>' . ' : ' . $supplier . '</p>';
        return $html;
    }

    public function purchaseReturnProductTax($data)
    {
        $purchaseReturnProduct = $data?->purchaseReturnProduct;

        $supplier = $purchaseReturnProduct?->salesReturn?->supplier?->name;
        $product = $purchaseReturnProduct?->product?->name;
        $variant = $purchaseReturnProduct?->variant ? ' - ' . $purchaseReturnProduct?->variant?->variant_name : '';
        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">' . __('Input Vat/Tax By Sales Returned Product.') . '</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . __('Customer') . '</b>' . ' : ' . $supplier . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('By') . '</b>' . ' : ' . $product . $variant . '</p>';
        return $html;
    }

    public function salesOnAmount($data)
    {
        $amount = $data?->sale?->total_invoice_amount;
        $due = $data?->sale?->due;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($amount) . '</span>' . ' | ' . __('Due') . '<span class="text-danger fw-bold"> :' . \App\Utils\Converter::format_in_bdt($due) . '</span>';
        return $html;
    }

    public function saleProductOnAmount($data)
    {
        $soldProductAmount = $data?->saleProduct?->subtotal;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($soldProductAmount) . '</span>';
        return $html;
    }

    public function purchaseReturnOnAmount($data)
    {
        $purchaseReturnedAmount = $data?->purchaseReturn?->total_return_amount;
        $due = $data?->purchaseReturn?->due;

        $html = '<span class="text-danger fw-bold">' . \App\Utils\Converter::format_in_bdt($purchaseReturnedAmount) . '</span>' . ' | ' . __('Due') . '<span class="text-danger fw-bold"> :' . \App\Utils\Converter::format_in_bdt($due) . '</span>';
        return $html;
    }

    public function purchaseReturnProductOnAmount($data)
    {
        $purchaseReturnedProductAmount = $data?->purchaseReturnProduct?->return_subtotal;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($purchaseReturnedProductAmount) . '</span>';
        return $html;
    }

    private function branchName($data)
    {
        $generalSettings = config('generalSettings');

        if ($data->branch) {

            $areaName = $data?->branch_area_name ? '(' . $data?->branch_area_name . ')' : '';

            if ($data?->parent_branch_name) {

                return $data?->parent_branch_name . $areaName;
            } else {

                return $data?->branch_name . $areaName;
            }
        } else {

            return $generalSettings['business_or_shop__business_name'] . '(' . __('Company') . ')';
        }
    }
}
