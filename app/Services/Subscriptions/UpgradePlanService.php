<?php

namespace App\Services\Subscriptions;

use App\Enums\BooleanType;
use Modules\SAAS\Utils\PlanPriceIfLocationIsBd;

class UpgradePlanService
{
    public function prepareAmounts(object $plan, object $branches, object $leftBranchExpireDateHistories, object $currentSubscription)
    {
        $totalAdjustableAmount = 0;
        $totalPrice = 0;
        foreach ($branches as $branch) {
            $shopExpireDateHistory = $branch->shopExpireDateHistory;
            if ($shopExpireDateHistory->price_period == 'lifetime') {

                $totalPrice += PlanPriceIfLocationIsBd::amount($plan->lifetime_price);
            } elseif ($shopExpireDateHistory->price_period == 'month') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
                $totalPrice += $leftMonth * PlanPriceIfLocationIsBd::amount($plan->price_per_month);
            } elseif ($shopExpireDateHistory->price_period == 'year') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
                $totalPrice += PlanPriceIfLocationIsBd::amount(($plan->price_per_year / 12) * $leftMonth);
            }

            if ($shopExpireDateHistory->price_period == 'lifetime') {

                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - PlanPriceIfLocationIsBd::amount($shopAdjustablePrice);
                $branch->adjusted_amount = PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - PlanPriceIfLocationIsBd::amount($shopAdjustablePrice);
            } elseif ($shopExpireDateHistory->price_period == 'month') {

                $shopAdjustablePrice = $branch->shopExpireDateHistory->adjustable_price;
                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
                $branch->adjusted_amount = PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
            } elseif ($shopExpireDateHistory->price_period == 'year') {

                $shopAdjustablePrice = $branch->shopExpireDateHistory->adjustable_price;
                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $shopExpireDateHistory->expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
                $branch->adjusted_amount = PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
            }
        }

        foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory) {

            if ($leftBranchExpireDateHistory->price_period == 'lifetime') {

                $totalPrice += PlanPriceIfLocationIsBd::amount($plan->lifetime_price);
            } elseif ($leftBranchExpireDateHistory->price_period == 'month') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalPrice += $leftMonth * PlanPriceIfLocationIsBd::amount($plan->price_per_month);
            } elseif ($leftBranchExpireDateHistory->price_period == 'year') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalPrice += PlanPriceIfLocationIsBd::amount($plan->price_per_year / 12) * $leftMonth;
            }

            if ($leftBranchExpireDateHistory->price_period == 'lifetime') {

                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - PlanPriceIfLocationIsBd::amount($currentSubscription->adjustable_price);

                $leftBranchExpireDateHistory->adjusted_amount = PlanPriceIfLocationIsBd::amount($plan->lifetime_price) - PlanPriceIfLocationIsBd::amount($currentSubscription->adjustable_price);
            } elseif ($leftBranchExpireDateHistory->price_period == 'month') {

                $shopAdjustablePrice = $leftBranchExpireDateHistory->adjustable_price;
                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;

                $leftBranchExpireDateHistory->adjusted_amount = PlanPriceIfLocationIsBd::amount($shopAdjustablePrice) * $leftMonth;
            } elseif ($leftBranchExpireDateHistory->price_period == 'year') {

                $shopAdjustablePrice = $leftBranchExpireDateHistory->adjustable_price;
                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $leftBranchExpireDateHistory->expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;

                $leftBranchExpireDateHistory->adjusted_amount = PlanPriceIfLocationIsBd::amount($shopAdjustablePrice / 12) * $leftMonth;
            }
        }

        if ($currentSubscription->has_business == BooleanType::True->value) {

            if ($currentSubscription->business_price_period == 'lifetime') {

                $totalPrice += $plan->business_lifetime_price;
            } elseif ($currentSubscription->business_price_period == 'month') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
                $totalPrice += $leftMonth * PlanPriceIfLocationIsBd::amount($plan->business_price_per_month);
            } elseif ($currentSubscription->business_price_period == 'year') {

                $leftMonth = $this->getMonthsForTotalPriceCalculation(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
                $totalPrice += PlanPriceIfLocationIsBd::amount($plan->business_price_per_year / 12) * $leftMonth;
            }

            if ($currentSubscription->business_price_period == 'lifetime') {

                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price) - PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price);

                $currentSubscription->adjusted_amount = PlanPriceIfLocationIsBd::amount($plan->business_lifetime_price) - PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price);
            } elseif ($currentSubscription->business_price_period == 'month') {

                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price) * $leftMonth;

                $currentSubscription->adjusted_amount = PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price) * $leftMonth;
            } elseif ($currentSubscription->business_price_period == 'year') {

                $leftMonth = $this->getMonths(startDate: date('Y-m-d'), endDate: $currentSubscription->business_expire_date);
                $totalAdjustableAmount += PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price / 12) * $leftMonth;

                $currentSubscription->adjusted_amount = PlanPriceIfLocationIsBd::amount($currentSubscription->business_adjustable_price / 12) * $leftMonth;
            }
        }

        $subtotal = $totalPrice - $totalAdjustableAmount;

        return [
            'totalAdjustableAmount' => round($totalAdjustableAmount, 0),
            'totalPrice' => round($totalPrice, 0),
            'subtotal' => round($subtotal, 0),
        ];
    }

    private function getMonths($startDate, $endDate)
    {
        if ($startDate <= $endDate) {

            $startDate = new \DateTime($startDate);
            $endDate = new \DateTime($endDate);

            // Calculate the difference in months
            $interval = $startDate->diff($endDate);
            $months = $interval->y * 12 + $interval->m;
            return (int) $months;
        } else {

            return 0;
        }
    }

    private function getMonthsForTotalPriceCalculation($startDate, $endDate)
    {
        if ($startDate <= $endDate) {
            $start_date = new \DateTime($startDate);
            $end_date = new \DateTime($endDate);

            // Calculate the difference in days
            $interval = $start_date->diff($end_date);
            $days_difference = $interval->days + 1;

            // Calculate the total number of days in the month
            $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, $start_date->format('m'), $start_date->format('Y'));

            // Calculate the fraction of a month
            return $days_difference / $total_days_in_month;
        } else {

            return 0;
        }
    }
}
