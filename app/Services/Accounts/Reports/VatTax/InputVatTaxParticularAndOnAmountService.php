<?php

namespace App\Services\Accounts\Reports\VatTax;

use App\Enums\AccountLedgerVoucherType;

class InputVatTaxParticularAndOnAmountService
{
    public function particulars($voucherType, $data)
    {
        if ($voucherType == AccountLedgerVoucherType::Purchase->value) {
            return $this->purchaseDetails($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseProductTax->value) {
            return $this->purchaseProductTax($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SalesReturn->value) {
            return $this->salesReturnDetails($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SalesReturnProductTax->value) {
            return $this->saleReturnProductTax($data);
        }
    }

    public function onAmounts($voucherType, $data)
    {
        if ($voucherType == AccountLedgerVoucherType::Purchase->value) {
            return $this->purchaseOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::PurchaseProductTax->value) {
            return $this->purchaseProductOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SalesReturn->value) {
            return $this->salesReturnOnAmount($data);
        } elseif ($voucherType == AccountLedgerVoucherType::SalesReturnProductTax->value) {
            return $this->saleReturnProductOnAmount($data);
        }
    }

    public function purchaseDetails($data)
    {
        $supplier = $data?->purchase?->supplier?->name;

        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">(' . __('Input Vat/Tax On Purchase Invoice.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('Supplier') . '</b>' . ' : ' . $supplier . '</p>';
        return $html;
    }

    public function purchaseProductTax($data)
    {
        $purchaseProduct = $data?->purchaseProduct;

        $supplier = $purchaseProduct?->purchase?->supplier?->name;
        $product = $purchaseProduct?->product?->name;
        $variant = $purchaseProduct?->variant ? ' - ' . $purchaseProduct?->variant?->variant_name : '';
        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 fw-bold mb-1 mt-1" style="line-height:1.3!important;">(' . __('Input Vat/Tax On Purchased Product.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . __('Supplier') . '</b>' . ' : ' . $supplier . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('By') . '</b>' . ' : ' . $product . $variant . '</p>';
        return $html;
    }

    public function salesReturnDetails($data)
    {
        $customer = $data?->salesReturn?->customer?->name;

        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">(' . __('Input Vat/Tax By Sales Return Voucher.') . ')</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('Customer') . '</b>' . ' : ' . $customer . '</p>';
        return $html;
    }

    public function saleReturnProductTax($data)
    {
        $salesReturnProduct = $data?->salesReturnProduct;

        $customer = $salesReturnProduct?->salesReturn?->customer?->name;
        $product = $salesReturnProduct?->product?->name;
        $variant = $salesReturnProduct?->variant ? ' - ' . $salesReturnProduct?->variant?->variant_name : '';
        // $branchName = $this->branchName($data);

        $html = '';
        $html .= '<p class="p-0 m-0 mt-1 mb-1 fw-bold" style="line-height:1.3!important;">' . __('Input Vat/Tax By Sales Returned Product.') . '</p>';
        // $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . $branchName . '</b>' . '</p>';
        $html .= '<p class="p-0 m-0" style="line-height:1.3!important;">' . '<b>' . __('Customer') . '</b>' . ' : ' . $customer . '</p>';
        $html .= '<p class="p-0 m-0 mb-1" style="line-height:1.3!important;">' . '<b>' . __('By') . '</b>' . ' : ' . $product . $variant . '</p>';
        return $html;
    }

    public function purchaseOnAmount($data)
    {
        $purchasedAmount = $data?->purchase?->total_purchase_amount;
        $due = $data?->purchase?->due;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($purchasedAmount) . '</span>' . ' | ' . __('Due') . '<span class="text-danger fw-bold"> :' . \App\Utils\Converter::format_in_bdt($due) . '</span>';
        return $html;
    }

    public function purchaseProductOnAmount($data)
    {
        $purchasedProductAmount = $data?->purchaseProduct?->line_total;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($purchasedProductAmount) . '</span>';
        return $html;
    }

    public function salesReturnOnAmount($data)
    {
        $salesReturnedAmount = $data?->salesReturn?->total_return_amount;
        $due = $data?->salesReturn?->due;

        $html = '<span class="text-danger fw-bold">' . \App\Utils\Converter::format_in_bdt($salesReturnedAmount) . '</span>' . ' | ' . __('Due') . '<span class="text-danger fw-bold"> :' . \App\Utils\Converter::format_in_bdt($due) . '</span>';
        return $html;
    }

    public function saleReturnProductOnAmount($data)
    {
        $salesReturnedProductAmount = $data?->salesReturnProduct?->return_subtotal;

        $html = '<span class="fw-bold">' . \App\Utils\Converter::format_in_bdt($salesReturnedProductAmount) . '</span>';
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
