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
                    GeneralSetting::where('key', $key)->update(['value' => $value]);
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
}
