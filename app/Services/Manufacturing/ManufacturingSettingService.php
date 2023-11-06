<?php

namespace App\Services\Manufacturing;

use App\Models\Manufacturing\ManufacturingSetting;

class ManufacturingSettingService
{
    public function manufacturingSettingAddOrUpdate(object $request): void
    {

        $mfSetting = $this->manufacturingSetting()->where('branch_id', auth()->user()->branch_id)->first();

        $addOrUpdateMfSetting = '';
        if ($mfSetting) {

            $addOrUpdateMfSetting = $mfSetting;
        } else {

            $addOrUpdateMfSetting = new ManufacturingSetting();
        }

        $addOrUpdateMfSetting->branch_id = auth()->user()->branch_id;
        $addOrUpdateMfSetting->production_voucher_prefix = $request->production_voucher_prefix;
        $addOrUpdateMfSetting->is_edit_ingredients_qty_in_production = $request->is_edit_ingredients_qty_in_production;
        $addOrUpdateMfSetting->is_update_product_cost_and_price_in_production = $request->is_update_product_cost_and_price_in_production;

        $addOrUpdateMfSetting->save();
    }

    public function manufacturingSetting(array $with = null)
    {
        $query = ManufacturingSetting::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
