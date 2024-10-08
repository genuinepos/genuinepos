<?php

namespace Modules\SAAS\Services;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\SubscriptionUpdateType;
use Modules\SAAS\Entities\UserSubscription;
use Modules\SAAS\Utils\ExpireDateAllocation;
use Modules\SAAS\Interfaces\UserServiceInterface;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;

class UserSubscriptionService implements UserSubscriptionServiceInterface
{
    public function addUserSubscription(object $request, int $subscriberUserId, ?object $plan): object
    {
        $addUserSubscription = new UserSubscription();
        $addUserSubscription->user_id = $subscriberUserId;
        $addUserSubscription->plan_id = $plan->id;
        $addUserSubscription->status = BooleanType::True->value;
        $addUserSubscription->initial_plan_start_date = Carbon::now();
        $addUserSubscription->current_shop_count = $plan->is_trial_plan == BooleanType::True->value ? $plan->trial_shop_count : $request->shop_count;

        if ($plan->is_trial_plan == BooleanType::False->value) {

            if (isset($request->has_business)) {

                $addUserSubscription->has_business = BooleanType::True->value;
                $addUserSubscription->business_start_date = Carbon::now();
                $addUserSubscription->business_price_period = $request->business_price_period;
                $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                $addUserSubscription->business_expire_date = $expireDate;
            }

            $addUserSubscription->has_due_amount = $request->payment_status == BooleanType::False->value ? BooleanType::True->value : BooleanType::False->value;
            if ($request->payment_status == BooleanType::False->value) {

                $addUserSubscription->due_repayment_date = $request->repayment_date ? date('Y-m-d', strtotime($request->repayment_date)) : null;
            }
        } elseif ($plan->is_trial_plan == BooleanType::True->value) {

            $addUserSubscription->trial_start_date = Carbon::now();
            $addUserSubscription->has_business = BooleanType::True->value;
        }

        $addUserSubscription->save();

        return $addUserSubscription;
    }

    public function updateUserSubscription(?int $id, object $request, ?object $plan = null, int $isTrialPlan = 0, int $subscriptionUpdateType = 1): ?object
    {
        $updateUserSubscription = $this->singleUserSubscription(id: $id);

        if (isset($updateUserSubscription)) {

            $paymentStatus = isset($request->payment_status) && $request->payment_status == BooleanType::True->value ? BooleanType::True->value : BooleanType::False->value;

            $updateUserSubscription->trial_start_date = null;

            if ($isTrialPlan == BooleanType::True->value && $subscriptionUpdateType != SubscriptionUpdateType::UpdateExpireDate->value) {

                $updateUserSubscription->has_due_amount = $paymentStatus == BooleanType::True->value ? BooleanType::False->value : BooleanType::True->value;

                $repaymentDate = isset($request->repayment_date) ? date('Y-m-d', strtotime($request->repayment_date)) : null;
                $updateUserSubscription->due_repayment_date = $paymentStatus == BooleanType::False->value ? $repaymentDate : null;
            }

            if ($subscriptionUpdateType == SubscriptionUpdateType::UpgradePlan->value) {

                $updateUserSubscription->plan_id = $plan->id;
                $updateUserSubscription->initial_plan_start_date = Carbon::now();

                if ($isTrialPlan == BooleanType::True->value) {

                    $updateUserSubscription->current_shop_count = $request->shop_count;

                    if (isset($request->has_business)) {

                        $updateUserSubscription->has_business = BooleanType::True->value;
                        $updateUserSubscription->business_price_period = $request->business_price_period;
                        $updateUserSubscription->business_start_date = Carbon::now();

                        $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                        $updateUserSubscription->business_expire_date = $expireDate;
                    } else {

                        $updateUserSubscription->has_business = BooleanType::False->value;
                        $updateUserSubscription->business_price_period = null;
                        $updateUserSubscription->business_start_date = null;
                        $updateUserSubscription->business_expire_date = null;
                    }
                }
            } else if ($subscriptionUpdateType == SubscriptionUpdateType::AddShop->value) {

                $updateUserSubscription->current_shop_count = $updateUserSubscription->current_shop_count + $request->increase_shop_count;
            } else if ($subscriptionUpdateType == SubscriptionUpdateType::ShopRenew->value) {

                if ($request->has_business && $updateUserSubscription->has_business) {

                    $startDate = date('Y-m-d') <= $updateUserSubscription->business_expire_date ? $updateUserSubscription->business_expire_date : null;
                    $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count, startDate: $startDate);

                    $updateUserSubscription->business_price_period = $request->business_price_period;
                    $updateUserSubscription->business_expire_date = $expireDate;
                }
            } else if ($subscriptionUpdateType == SubscriptionUpdateType::AddBusiness->value) {

                $expireDate = ExpireDateAllocation::getExpireDate(period: $request->business_price_period == 'lifetime' ? 'year' : $request->business_price_period, periodCount: $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count);

                $updateUserSubscription->has_business = BooleanType::True->value;
                $updateUserSubscription->business_price_period = $request->business_price_period;
                $updateUserSubscription->business_start_date = Carbon::now();
                $updateUserSubscription->business_expire_date = $expireDate;
            } else if ($subscriptionUpdateType == SubscriptionUpdateType::UpdateExpireDate->value && $updateUserSubscription->has_business == BooleanType::True->value) {

                $updateUserSubscription->business_expire_date = date('Y-m-d', strtotime($request->business_new_expire_date));
            } else if ($subscriptionUpdateType == SubscriptionUpdateType::UpdatePaymentStatus->value) {

                $paymentStatus = isset($request->payment_status) && $request->payment_status == BooleanType::True->value ? BooleanType::True->value : BooleanType::False->value;

                $updateUserSubscription->has_due_amount = $paymentStatus == BooleanType::True->value ? BooleanType::False->value : BooleanType::True->value;

                $repaymentDate = isset($request->repayment_date) ? date('Y-m-d', strtotime($request->repayment_date)) : null;
                $updateUserSubscription->due_repayment_date = $paymentStatus == BooleanType::False->value ? $repaymentDate : null;
            }

            $updateUserSubscription->save();

            return $updateUserSubscription;
        }

        return null;
    }

    public function singleUserSubscription(?int $id, ?array $with = null)
    {
        $query = UserSubscription::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
