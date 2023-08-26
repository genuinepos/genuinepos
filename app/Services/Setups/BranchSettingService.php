<?php

namespace App\Services\Setups;

use App\Models\Setups\BranchSetting;

class BranchSettingService
{
    public function addBranchSettings(int $branchId, int $defaultInvoiceLayoutId, object $branchService) : void
    {
        $branch = $branchService->singleBranch(id: $branchId);

        $exp = explode(' ', $branch->name);

        $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
        $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

        $branchNamePrefix = strtoupper($str1.$str2);

        $addBranchSettings = new BranchSetting();
        $addBranchSettings->branch_id = $branchId;
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

    public function updateBranchSettings(int $branchId, object $request) : void
    {
        $updateBranchSettings = $this->singleBranchSetting(branchId: $branchId);
        $updateBranchSettings->invoice_prefix = $request->invoice_prefix;
        $updateBranchSettings->quotation_prefix = $request->quotation_prefix;
        $updateBranchSettings->sales_order_prefix = $request->sales_order_prefix;
        $updateBranchSettings->sales_return_prefix = $request->sales_return_prefix;
        $updateBranchSettings->payment_voucher_prefix = $request->payment_voucher_prefix;
        $updateBranchSettings->receipt_voucher_prefix = $request->receipt_voucher_prefix;
        $updateBranchSettings->purchase_invoice_prefix = $request->purchase_invoice_prefix;
        $updateBranchSettings->purchase_order_prefix = $request->purchase_order_prefix;
        $updateBranchSettings->purchase_return_prefix = $request->purchase_return_prefix;
        $updateBranchSettings->stock_adjustment_prefix = $request->stock_adjustment_prefix;
        $updateBranchSettings->add_sale_invoice_layout_id = $request->add_sale_invoice_layout_id;
        $updateBranchSettings->pos_sale_invoice_layout_id = $request->pos_sale_invoice_layout_id;
        $updateBranchSettings->default_tax_ac_id = $request->default_tax_ac_id;
        $updateBranchSettings->save();
    }

    public function singleBranchSetting(int $branchId, array $with = null)
    {
        $query = BranchSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('branch_id', $branchId)->first();
    }
}
