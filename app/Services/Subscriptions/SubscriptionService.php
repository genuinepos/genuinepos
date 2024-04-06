<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\SubscriptionUpdateType;
use App\Models\Subscriptions\Subscription;
use Modules\SAAS\Utils\ExpireDateAllocation;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;

class SubscriptionService
{
    public function updateBusinessStartUpCompletingStatus()
    {
        $updateSubscription = Subscription::first();
        $updateSubscription->is_completed_business_startup = BooleanType::True->value;
        $updateSubscription->save();
    }

    public function updateBranchStartUpCompletingStatus()
    {
        $updateSubscription = Subscription::first();
        $updateSubscription->is_completed_branch_startup = BooleanType::True->value;
        $updateSubscription->save();
    }

    public function addSubscription(object $request, object $plan): object
    {
        $addSubscription = new Subscription();
        $addSubscription->user_id = 1;
        $addSubscription->plan_id = $plan->id;
        $addSubscription->status = BooleanType::True->value;
        $addSubscription->initial_plan_start_date = Carbon::now();
        $addSubscription->current_shop_count = $plan->is_trial_plan == BooleanType::True->value ? $plan->trial_shop_count : $request->shop_count;

        if ($plan->is_trial_plan == BooleanType::False->value) {

            if (isset($request->has_business)) {

                $addSubscription->has_business = BooleanType::True->value;
                $addSubscription->business_start_date = Carbon::now();
                $addSubscription->business_price_period = $request->business_price_period;
                $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                $addSubscription->business_expire_date = $expireDate;

                $business = isset($request->has_business) ? 1 : 0;
                $shopPlusBusiness = $request->shop_count + $business;
                $totalPayableInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->total_payable);
                $adjustablePrice = $totalPayableInUsd / $shopPlusBusiness;

                $addSubscription->business_adjustable_price = $adjustablePrice;
            }

            $addSubscription->has_due_amount = $request->payment_status == BooleanType::False->value ? BooleanType::True->value : BooleanType::False->value;
            if ($request->payment_status == BooleanType::False->value) {

                $addSubscription->due_repayment_date = $request->repayment_date ? date('Y-m-d', strtotime($request->repayment_date)) : null;
            }
        } elseif ($plan->is_trial_plan == BooleanType::True->value) {

            $addSubscription->trial_start_date = Carbon::now();
            $addSubscription->has_business = BooleanType::True->value;
        }

        $addSubscription->save();

        return $addSubscription;
    }

    public function updateSubscription(object $request, ?object $plan = null, int $isTrialPlan = 0, int $subscriptionUpdateType = 1): object
    {
        $updateSubscription = $this->singleSubscription();

        $updateSubscription->trial_start_date = null;
        $updateSubscription->has_due_amount = BooleanType::False->value;
        $updateSubscription->due_repayment_date = null;

        if ($subscriptionUpdateType == SubscriptionUpdateType::UpgradePlan->value) {

            $updateSubscription->plan_id = $plan?->id;
            $updateSubscription->initial_plan_start_date = Carbon::now();

            if ($isTrialPlan == BooleanType::True->value) {

                $updateSubscription->current_shop_count = $request->shop_count;
                $updateSubscription->is_completed_business_startup = BooleanType::False->value;
                $updateSubscription->is_completed_branch_startup = BooleanType::False->value;

                if (isset($request->has_business)) {

                    $updateSubscription->has_business = BooleanType::True->value;
                    $updateSubscription->business_price_period = $request->business_price_period;
                    $updateSubscription->business_start_date = Carbon::now();

                    $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                    $business = isset($request->has_business) ? 1 : 0;
                    $shopPlusBusiness = $request->shop_count + $business;
                    $totalPayableInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->total_payable);
                    $adjustablePrice = $totalPayableInUsd / $shopPlusBusiness;

                    $updateSubscription->business_adjustable_price = $adjustablePrice;
                    $updateSubscription->business_expire_date = $expireDate;
                } else {

                    $updateSubscription->has_business = BooleanType::False->value;
                    $updateSubscription->business_price_period = null;
                    $updateSubscription->business_start_date = null;
                    $updateSubscription->business_expire_date = null;
                }
            } else {

                if ($updateSubscription->has_business == BooleanType::True->value) {

                    $currentBusinessPrice = 0;
                    if ($updateSubscription->business_price_period == 'month') {

                        $currentBusinessPrice = $plan->business_price_per_month;
                    } elseif ($updateSubscription->business_price_period == 'year') {

                        $currentBusinessPrice = $plan->business_price_per_year;
                    } elseif ($updateSubscription->business_price_period == 'lifetime') {

                        $currentBusinessPrice = $plan->business_lifetime_price;
                    }

                    $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;

                    $discountAmount = ($currentBusinessPrice / 100) * $discountPercent;
                    $currentAdjustablePrice = $currentBusinessPrice - $discountAmount;
                    $updateSubscription->business_adjustable_price = $currentAdjustablePrice;
                }
            }
        } else if ($subscriptionUpdateType == SubscriptionUpdateType::AddShop->value) {

            $updateSubscription->current_shop_count = $updateSubscription->current_shop_count + $request->increase_shop_count;
        }

        $updateSubscription->save();
        return $updateSubscription;
    }

    public function singleSubscription(array $with = null)
    {
        $query = Subscription::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->first();
    }
}
