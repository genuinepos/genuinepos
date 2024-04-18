<?php

namespace App\Services\Setups;

use App\Enums\BooleanType;
use Carbon\Carbon;
use Modules\SAAS\Utils\ExpireDateAllocation;
use App\Models\Subscriptions\ShopExpireDateHistory;

class ShopExpireDateHistoryService
{
    public function addShopExpireDateHistory(string $shopPricePeriod, int $shopPricePeriodCount, object $plan, float $adjustablePrice): void
    {
        $expireDate = '';
        if ($shopPricePeriod == 'month') {

            $expireDate = ExpireDateAllocation::getExpireDate(period: 'month', periodCount: $shopPricePeriodCount);
        } else if ($shopPricePeriod == 'year') {

            $expireDate = ExpireDateAllocation::getExpireDate(period: 'year', periodCount: $shopPricePeriodCount);
        } else if ($shopPricePeriod == 'lifetime') {

            $expireDate = ExpireDateAllocation::getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
        }

        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->price_period = $shopPricePeriod;
        $shopHistory->adjustable_price = $adjustablePrice;
        $shopHistory->start_date = Carbon::now();
        $shopHistory->expire_date = $expireDate;
        $shopHistory->is_created = BooleanType::False->value;
        $shopHistory->save();
    }

    function updateShopExpireDateHistory(int $id, ?string $shopPricePeriod = null, ?float $adjustablePrice = null, ?string $expireDate = null, ?bool $isCreated = null): void
    {
        $updateShopExpireDateHistory = $this->singleShopExpireDateHistory(id: $id, with: ['branch']);
        if (isset($updateShopExpireDateHistory)) {

            $updateShopExpireDateHistory->price_period = isset($shopPricePeriod) ? $shopPricePeriod : $updateShopExpireDateHistory->price_period;
            $updateShopExpireDateHistory->adjustable_price = isset($adjustablePrice) ? $adjustablePrice : $updateShopExpireDateHistory->adjustable_price;
            $updateShopExpireDateHistory->expire_date = isset($expireDate) ? $expireDate : $updateShopExpireDateHistory->expire_date;
            $updateShopExpireDateHistory->is_created = isset($isCreated) ? $isCreated : $updateShopExpireDateHistory->is_created;
            $updateShopExpireDateHistory->save();

            if ($updateShopExpireDateHistory?->branch && isset($expireDate)) {

                $updateShopExpireDateHistory->branch->expire_date = $expireDate;
                $updateShopExpireDateHistory->branch->save();
            }
        }
    }

    public function updateShopExpireDateHistoryAdjustablePriceAndPricePeriod(object $plan, int $shopExpireDateHistoryId, ?float $discountPercent = null, ?string $shopPricePeriod = null)
    {
        $shopExpireDateHistory = $this->singleShopExpireDateHistory(id: $shopExpireDateHistoryId);
        $pricePeriod = isset($shopPricePeriod) ? $shopPricePeriod : $shopExpireDateHistory->price_period;

        $discountPercent = isset($discountPercent) ? $discountPercent : 0;
        $currentShopPrice = 0;
        if ($pricePeriod == 'month') {

            $currentShopPrice = $plan->price_per_month;
        } elseif ($pricePeriod == 'year') {

            $currentShopPrice = $plan->price_per_year;
        } elseif ($pricePeriod == 'lifetime') {

            $currentShopPrice = $plan->lifetime_price;
        }

        $discountAmount = ($currentShopPrice / 100) * $discountPercent;
        $currentAdjustablePrice = round($currentShopPrice - $discountAmount, 0);

        $shopExpireDateHistory->price_period = $pricePeriod;
        $shopExpireDateHistory->adjustable_price = $currentAdjustablePrice;
        $shopExpireDateHistory->save();
    }

    public function singleShopExpireDateHistory(int $id, array $with = null)
    {
        $query = ShopExpireDateHistory::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function shopExpireDateHistoryByAnyCondition(array $with = null)
    {
        $query = ShopExpireDateHistory::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function shopExpireDateHistories(array $with = null)
    {
        $query = ShopExpireDateHistory::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
