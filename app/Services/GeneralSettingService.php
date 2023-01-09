<?php
namespace App\Services;

use App\Models\GeneralSetting;
use App\Services\CacheServiceInterface;
use App\Services\GeneralSettingServiceInterface;

class GeneralSettingService implements GeneralSettingServiceInterface
{
    public function __construct(
        private CacheServiceInterface $cacheService
    ) {}
    
    public function updateAndSync(array $settings): bool
    {
        if(is_array($settings)) {
            foreach($settings as $key => $value) {
                if(isset($key) && isset($value)) {
                    GeneralSetting::where('key', $key)->update(["value" => $value]);
                }
            }
            $this->cacheService->syncGeneralSettings();
            return true;
        }
        return false;
    }
}