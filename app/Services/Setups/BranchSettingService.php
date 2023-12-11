<?php

namespace App\Services\Setups;

use App\Models\Setups\BranchSetting;

class BranchSettingService
{
    public function addBranchSettings(int $branchId, int $parentBranchId = null, int $defaultInvoiceLayoutId, object $branchService): void
    {
        $branch = $branchService->singleBranch(id: $parentBranchId ? $parentBranchId : $branchId);

        $exp = explode(' ', $branch->name);

        $str1 = isset($exp[0]) ? str_split($exp[0])[0] : '';
        $str2 = isset($exp[1]) ? str_split($exp[1])[0] : '';

        $branchNamePrefix = strtoupper($str1 . $str2);

        $addBranchSettings = new BranchSetting();
        $addBranchSettings->branch_id = $branchId;
        $addBranchSettings->invoice_prefix = $branchNamePrefix;
        $addBranchSettings->quotation_prefix = $branchNamePrefix . 'Q';
        $addBranchSettings->sales_order_prefix = $branchNamePrefix . 'SO';
        $addBranchSettings->sales_return_prefix = $branchNamePrefix . 'SR';
        $addBranchSettings->payment_voucher_prefix = $branchNamePrefix . 'PV';
        $addBranchSettings->receipt_voucher_prefix = $branchNamePrefix . 'RV';
        $addBranchSettings->purchase_invoice_prefix = $branchNamePrefix . 'PI';
        $addBranchSettings->purchase_order_prefix = $branchNamePrefix . 'PO';
        $addBranchSettings->purchase_return_prefix = $branchNamePrefix . 'PRV';
        $addBranchSettings->add_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        $addBranchSettings->pos_sale_invoice_layout_id = $defaultInvoiceLayoutId;
        $addBranchSettings->save();
    }

    public function updateAndSync(array $settings, int $branchId): bool
    {
        if (is_array($settings)) {

            foreach ($settings as $key => $value) {

                if (isset($key) && isset($value)) {

                    $branchSetting = BranchSetting::where('branch_id', $branchId)->where('key', $key)->first();
                    if ($branchSetting) {

                        $branchSetting->key = $key;
                        $branchSetting->value = $value;
                        if ($key == 'invoice_layout__add_sale_invoice_layout_id') {

                            $branchSetting->add_sale_invoice_layout_id = $value;
                        } else if ($key == 'invoice_layout__pos_sale_invoice_layout_id') {

                            $branchSetting->pos_sale_invoice_layout_id = $value;
                        }

                        $branchSetting->save();
                    } else {

                        $addBranchSetting = new BranchSetting();
                        $addBranchSetting->key = $key;
                        $addBranchSetting->value = $value;

                        if ($key == 'invoice_layout__add_sale_invoice_layout_id') {

                            $addBranchSetting->add_sale_invoice_layout_id = $value;
                        } else if ($key == 'invoice_layout__pos_sale_invoice_layout_id') {

                            $addBranchSetting->pos_sale_invoice_layout_id = $value;
                        }

                        $addBranchSetting->branch_id = $branchId;
                        $addBranchSetting->save();
                    }
                }
            }

            return true;
        }

        return false;
    }

    public function singleBranchSetting(?int $branchId, array $with = null)
    {
        $query = BranchSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('branch_id', $branchId)->first();
    }
}
