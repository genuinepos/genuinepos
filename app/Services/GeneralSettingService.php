<?php

namespace App\Services;

use App\Models\GeneralSetting;

class GeneralSettingService implements GeneralSettingServiceInterface
{
    public function __construct(
        private CacheServiceInterface $cacheService
    ) {
    }

    public function updateAndSync(array $settings): bool
    {
        if (is_array($settings)) {

            foreach ($settings as $key => $value) {

                if (isset($key) && isset($value)) {

                    if (
                        ($key == 'payroll_voucher_prefix' || $key == 'payroll_payment_voucher_prefix') &&
                        config('generalSettings')['subscription']->features['hrm'] == 0
                    ) {
                        continue;
                    }

                    GeneralSetting::where('key', $key)->where('branch_id', null)->update(['value' => $value]);
                }
            }

            $this->cacheService->syncGeneralSettings();

            return true;
        }

        return false;
    }

    public function generalSettings(?int $branchId = null, array $keys = null): ?array
    {
        $query = GeneralSetting::where('branch_id', $branchId);

        if (isset($key)) {

            $query->whereIn('key', $keys);
        }

        return $query->pluck('value', 'key')->toArray();
    }

    public function generalSettingsPermission(): ?bool
    {
        if (
            !auth()->user()->can('business_or_shop_settings') &&
            !auth()->user()->can('dashboard_settings') &&
            !auth()->user()->can('product_settings') &&
            !auth()->user()->can('purchase_settings') &&
            !auth()->user()->can('manufacturing_settings') &&
            !auth()->user()->can('add_sale_settings') &&
            !auth()->user()->can('pos_sale_settings') &&
            !auth()->user()->can('prefix_settings') &&
            !auth()->user()->can('invoice_layout_settings') &&
            !auth()->user()->can('print_settings') &&
            !auth()->user()->can('system_settings') &&
            !auth()->user()->can('reward_point_settings') &&
            !auth()->user()->can('module_settings') &&
            !auth()->user()->can('send_email_settings') &&
            !auth()->user()->can('send_sms_settings')
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function deleteBusinessLogo(): bool
    {
        $businessLogo = $this->singleGeneralSetting(key: 'business_or_shop__business_logo', branchId: null);

        if (isset($businessLogo->value)) {

            if (file_exists(public_path('uploads/business_logo/' . $businessLogo->value))) {

                unlink(public_path('uploads/business_logo/' . $businessLogo->value));
            }
        }

        $businessLogo->value = null;
        $businessLogo->save();

        return true;
    }

    public function singleGeneralSetting(string $key = null, ?int $branchId = null): ?object
    {
        return GeneralSetting::where('key', $key)->where('branch_id', $branchId)->first();
    }
}
