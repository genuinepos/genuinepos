<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Subscriptions\Subscription;

class SubscriptionService
{
    public function updateBusinessStartUpCompletingStatus()
    {
        $subscription = Subscription::first();
        $subscription->is_completed_business_startup = BooleanType::True->value;
        $subscription->save();
    }

    public function updateBranchStartUpCompletingStatus()
    {
        $subscription = Subscription::first();
        $subscription->is_completed_branch_startup = BooleanType::True->value;
        $subscription->save();
    }

    public function updateSubscription(object $request, object $plan, bool $isTrialPlan, object $expireDateCalculation): object
    {
        $subscription = $this->singleSubscription();
        $subscription->plan_id = $plan->id;
        $subscription->trial_start_date = null;
        $subscription->initial_plan_start_date = Carbon::now();
        $subscription->has_due_amount = BooleanType::False->value;
        $subscription->due_repayment_date = null;
        if ($isTrialPlan == BooleanType::True->value) {

            $subscription->current_shop_count = $request->shop_count;
            $subscription->is_completed_business_startup = BooleanType::False->value;
            $subscription->is_completed_branch_startup = BooleanType::False->value;

            if (isset($request->has_business)) {

                $subscription->has_business = BooleanType::True->value;
                $subscription->business_price_period = $request->business_price_period;
                $subscription->business_start_date = Carbon::now();

                $expireDate = $expireDateCalculation->getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                $business = isset($request->has_business) ? 1 : 0;
                $shopPlusBusiness = $request->shop_count + $business;
                $adjustablePrice = $request->total_payable / $shopPlusBusiness;

                $subscription->business_adjustable_price = $adjustablePrice;
                $subscription->business_expire_date = $expireDate;
            } else {

                $subscription->has_business = BooleanType::False->value;
                $subscription->business_price_period = null;
                $subscription->business_start_date = null;
                $subscription->business_expire_date = null;
            }
        }

        $subscription->save();
        return $subscription;
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
