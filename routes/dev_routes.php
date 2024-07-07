<?php

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

Route::get('my-test', function () {

    // $branchId = auth()->check() ? auth()->user()->branch_id : null;
    // $tenantId = tenant('id');  // Ensure this retrieves the current tenant's ID
    // $cacheKey = "{$tenantId}_generalSettings_{$branchId}";

    // Log::info("Checking cache for key: $cacheKey");

    // return Cache::rememberForever($cacheKey, function () use ($branchId, $cacheKey) {
    //     Log::info("Cache miss for key: $cacheKey, querying database");
    //     $settings = GeneralSetting::where('branch_id', $branchId)
    //         ->orWhereIn('key', [
    //             'business_or_shop__business_name',
    //             'business_or_shop__business_logo',
    //             'business_or_shop__address',
    //             'business_or_shop__email',
    //             'business_or_shop__phone',
    //         ])->pluck('value', 'key')->toArray();

    //     Log::info("Storing data in cache for key: $cacheKey");
    //     return $settings;
    // });
    $number = 10000000;
    return format_in_bdt($number);
});

Route::get('t-id', function () {
});
