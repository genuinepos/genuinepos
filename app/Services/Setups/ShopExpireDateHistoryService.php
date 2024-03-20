<?php

namespace App\Services\Setups;

use Carbon\Carbon;
use App\Models\Subscriptions\ShopExpireDateHistory;

class ShopExpireDateHistoryService
{
    public function addShopExpireDateHistory(object $request, object $expireDateCalculation): void
    {
        $expireDate = '';
        if ($request->shop_price_period == 'month') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'month', periodCount: $request->shop_price_period_count);
        } else if ($request->shop_price_period == 'year') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'year', periodCount: $request->shop_price_period_count);
        } else if ($request->shop_price_period == 'lifetime') {

            $expireDate = $expireDateCalculation->getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
        }

        $business = isset($request->has_business) ? 1 : 0;
        $shopPlusBusiness = $request->shop_count + $business;
        $adjustablePrice = $request->total_payable / $shopPlusBusiness;

        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->shop_count = $request->shop_count;
        $shopHistory->price_period = $request->shop_price_period;
        $shopHistory->adjustable_price = $adjustablePrice;
        $shopHistory->start_date = Carbon::now();
        $shopHistory->expire_date = $expireDate;
        $shopHistory->created_count = 0;
        $shopHistory->left_count = $request->shop_count;
        $shopHistory->save();
    }
}
