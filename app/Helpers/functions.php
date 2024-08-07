<?php

if (!function_exists('file_link')) {

    function file_link(string $fileType, ?string $fileName = null): string
    {
        $path = \App\Utils\FilePath::paths(fileType: $fileType);
        if (config('file_disk.name') == 'local') {

            return asset($path . $fileName);
        } else {

            return \Illuminate\Support\Facades\Storage::disk(config('file_disk.name'))->url($path . $fileName);
        }
    }
}

if (!function_exists('location_label')) {

    function location_label(?string $specificName = null): string
    {
        $business = __('Company');
        $store = __('Store');

        if (isset($specificName)) {

            if ($specificName == 'business') {

                return $business;
            } else if ($specificName == 'branch') {

                return $store;
            }
        }

        $storeWithBusiness = $store . '/' . $business;

        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == \App\Enums\BooleanType::False->value) {

            return $storeWithBusiness;
        } else {

            if (auth()->user()->branch_id) {

                return $store;
            } else {

                return $business;
            }
        }
    }
}

if (!function_exists('curr_cnv')) {

    function curr_cnv(?float $amount = null, ?float $rate = null, ?int $branchId = null): ?float
    {
        $__amount = isset($amount) ? $amount : 0;
        $__rate = isset($rate) ? $rate : 1;
        if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == \App\Enums\BooleanType::False->value && config('generalSettings')['subscription']->has_business == \App\Enums\BooleanType::True->value) {

            if (isset($amount) && $amount > 0 && isset($rate) && $rate > 0 && isset($branchId)) {

                return $__amount * $__rate;
            }
        }

        return $__amount;
    }
}
