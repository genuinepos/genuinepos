<?php

namespace App\Services\Setups;

use App\Models\Setups\BranchSetting;

class BranchSettingService
{
    public function addBranchSettings(int $branchId, $defaultInvoiceLayoutId, object $branchService): void
    {
        $branch = $branchService->singleBranch(id: $branchId);

        $exp = explode(' ', $branch->name);

        $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
        $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

        $branchNamePrefix = strtoupper($str1.$str2);

        $addBranchSettings = new BranchSetting();
        $addBranchSettings->invoice_prefix = $branchNamePrefix;
        $addBranchSettings->quotation_prefix = $branchNamePrefix.'Q';
        $addBranchSettings->sales_order_prefix = $branchNamePrefix.'SO';
        $addBranchSettings->sales_return_prefix = $branchNamePrefix.'SR';
        $addBranchSettings->payment_voucher_prefix = $branchNamePrefix.'PV';
        $addBranchSettings->receipt_voucher_prefix = $branchNamePrefix.'RV';
        $addBranchSettings->purchase_invoice_prefix = $branchNamePrefix.'PI';
        $addBranchSettings->purchase_order_prefix = $branchNamePrefix.'PO';
        $addBranchSettings->purchase_return_prefix = $branchNamePrefix.'PRV';
        $addBranchSettings->add_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        $addBranchSettings->pos_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        $addBranchSettings->save();
    }
}
