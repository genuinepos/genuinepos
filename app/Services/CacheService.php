<?php

namespace App\Services;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    public function forgetGeneralSettingsCache(?int $branchId = null): void
    {
        $__branchId = auth()?->user()?->branch_id ? auth()?->user()?->branch_id : null;
        if (isset($branchId)) {
            $__branchId = $branchId;
        }
        $cacheKey = "generalSettings_{$__branchId}";
        Cache::forget($cacheKey);
        $cacheKey = "parentBranchGeneralSettings_{$__branchId}";
        Cache::forget($cacheKey);
    }
}
