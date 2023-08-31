<?php

namespace App\Services\Products;

class ProductSettingsService
{
    function updateProductSettings(object $request, object $generalSettingService): void
    {
        $settings = [
            'product__product_code_prefix' => $request->product_code_prefix,
            'product__default_unit_id' => $request->default_unit_id,
            'product__is_enable_brands' => $request->is_enable_brands,
            'product__is_enable_categories' => $request->is_enable_categories,
            'product__is_enable_sub_categories' => $request->is_enable_sub_categories,
            'product__is_enable_price_tax' => $request->is_enable_price_tax,
            'product__is_enable_warranty' => $request->is_enable_warranty,
        ];

        $generalSettingService->updateAndSync($settings);
    }
}
