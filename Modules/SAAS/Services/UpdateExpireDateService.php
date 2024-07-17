<?php

namespace Modules\SAAS\Services;
use Carbon\Carbon;

class UpdateExpireDateService
{
    public function restrictions(object $request): array
    {
        $index = 1;
        if ($request->business_current_expire_date) {

            $businessCurrentExpireDate = date('Y-m-d', strtotime($request->business_current_expire_date));

            $businessNewExpireDate = date('Y-m-d', strtotime($request->business_new_expire_date));

            $__businessCurrentExpireDate = Carbon::parse($businessCurrentExpireDate);
            $__businessNewExpireDate = Carbon::parse($businessNewExpireDate);

            if ($__businessNewExpireDate->lt($__businessCurrentExpireDate)) {

                return ['pass' => false, 'msg' => __('Company New expire date must be greater then or equal current expired date')];
            }

            $index++;
        }

        foreach ($request->shop_new_expire_dates as $key => $shopNewExpireDate) {

            $shopCurrentExpireDate = date('Y-m-d', strtotime($request->shop_current_expire_dates[$key]));
            $shopNewExpireDate = date('Y-m-d', strtotime($shopNewExpireDate));

            $__shopCurrentExpireDate = Carbon::parse($shopCurrentExpireDate);
            $__shopNewExpireDate = Carbon::parse($shopNewExpireDate);

            if ($__shopNewExpireDate->lt($__shopCurrentExpireDate)) {

                return ['pass' => false, 'msg' => __("(S/L No: ${index}) Store New expire date must be greater then or equal current expired date")];
            }
            $index++;
        }

        return ['pass' => true];
    }
}
