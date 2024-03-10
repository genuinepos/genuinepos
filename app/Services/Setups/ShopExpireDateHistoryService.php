<?php

namespace App\Services\Setups;

use Carbon\Carbon;
use App\Models\Subscriptions\ShopExpireDateHistory;

class ShopExpireDateHistoryService
{
    public function addShopExpireDateHistory(object $request, object $expireDateCalculation) : void {

        $expireDate = '';
        if ($request->price_period == 'month') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'month', periodCount: $request->period_count);
        } else if ($request->price_period == 'year') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'year', periodCount: $request->period_count);
        } else if ($request->price_period == 'lifetime') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
        }

        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->shop_count = $request->shop_count;
        $shopHistory->price_period = $request->period_count;
        $shopHistory->start_date = Carbon::now();
        $shopHistory->expire_date = $expireDate;
        $shopHistory->created_count = 0;
        $shopHistory->left_count = $request->shop_count;
        $shopHistory->save();
    }
}
