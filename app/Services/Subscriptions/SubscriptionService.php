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
        $subscription->initial_payment_status = BooleanType::True->value;
        $subscription->initial_due_amount = 0;
        $subscription->initial_plan_expire_date = null;
        if ($isTrialPlan == BooleanType::True->value) {

            $subscription->initial_plan_start_date = Carbon::now();
            $subscription->initial_price_period = $request->price_period;
            $subscription->initial_period_count = $request->period_count == 'lifetime' ? $plan->applicable_lifetime_years : $request->period_count;

            $subscription->initial_shop_count = $request->shop_count;
            $subscription->current_shop_count = $request->shop_count;

            $subscription->initial_plan_price = $request->plan_price ? $request->plan_price : 0;
            $subscription->initial_subtotal = $request->subtotal ? $request->subtotal : 0;
            $subscription->initial_discount = $request->discount ? $request->discount : 0;
            $subscription->initial_total_payable_amount = $request->total_payable ? $request->total_payable : 0;
            $subscription->is_completed_business_startup = BooleanType::False->value;
            $subscription->is_completed_branch_startup = BooleanType::False->value;

            if (isset($request->has_business)) {

                $subscription->has_business = BooleanType::True->value;
                $subscription->initial_business_price_period = $request->business_price_period ? $request->business_price_period : 0;
                $subscription->initial_business_price = $request->business_price ? $request->business_price : 0;
                $subscription->initial_business_period_count = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_period_count;
                $subscription->initial_business_subtotal = $request->business_subtotal ? $request->business_subtotal : 0;
                $subscription->initial_business_start_date = Carbon::now();

                $expireDate = $expireDateCalculation->getExpireDate(period: $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_period_count);

                $subscription->business_expire_date = $expireDate;
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
