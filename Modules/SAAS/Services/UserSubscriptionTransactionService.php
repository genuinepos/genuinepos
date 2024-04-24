<?php

namespace Modules\SAAS\Services;

use Carbon\Carbon;
use App\Enums\BooleanType;
use Modules\SAAS\Entities\UserSubscriptionTransaction;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UserSubscriptionTransactionService implements UserSubscriptionTransactionServiceInterface
{
    public function userSubscriptionTransactions(array $with = null): object
    {
        $query = UserSubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function addUserSubscriptionTransaction(object $request, object $userSubscription, int $transactionType, string $transactionDetailsType, ?object $plan = null): void
    {
        $addTransaction = new UserSubscriptionTransaction();
        $addTransaction->transaction_type = $transactionType;
        $addTransaction->user_subscription_id = $userSubscription->id;
        $addTransaction->plan_id = $plan?->id;
        $addTransaction->payment_method_name = $request->payment_method_name;
        $addTransaction->payment_trans_id = $request->payment_trans_id;
        $addTransaction->net_total = isset($request->net_total) ? $request->net_total : 0;
        $addTransaction->coupon_code = isset($request->coupon_code) ? $request->coupon_code : 0;
        $addTransaction->discount_percent = isset($request->discount_percent) ? $request->discount_percent : 0;
        $addTransaction->discount = isset($request->discount) ? $request->discount : 0;
        $addTransaction->total_payable_amount = isset($request->total_payable) ? $request->total_payable : 0;
        $addTransaction->paid = $request->payment_status == BooleanType::True->value ? $request->total_payable : 0;
        $addTransaction->due = $request->payment_status == BooleanType::False->value ? $request->total_payable : 0;
        $addTransaction->payment_status = $request->payment_status;
        $addTransaction->payment_date = $request->payment_status == BooleanType::True->value ? Carbon::now() : null;
        $addTransaction->details_type = $transactionDetailsType;

        $transactionDetails = $this->transactionDetails(request: $request, detailsType: $transactionDetailsType, plan: $plan);
        $addTransaction->details = json_encode($transactionDetails);

        $addTransaction->save();
    }

    private function transactionDetails(object $request, string $detailsType, ?object $plan)
    {
        $gioInfo = GioInfo::getInfo();
        if ($detailsType == 'upgrade_plan_from_trial') {

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
            }

            return [
                'country' => $gioInfo['country'],
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => isset($request->shop_price) ? $request->shop_price : 0,
                'shop_subtotal' => isset($request->shop_subtotal) ? $request->shop_subtotal : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'direct_buy_plan') {

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
            }

            return [
                'country' => $gioInfo['country'],
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => isset($request->shop_price) ? $request->shop_price : 0,
                'shop_subtotal' => isset($request->shop_subtotal) ? $request->shop_subtotal : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'upgrade_plan_from_real_plan') {

            return [
                'country' => $gioInfo['country'],
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'total_adjusted_amount' => isset($request->total_adjusted_amount) ? $request->total_adjusted_amount : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'add_shop') {

            return [
                'country' => $gioInfo['country'],
                'increase_shop_count' => isset($request->increase_shop_count) ? $request->increase_shop_count : 0,
                'shop_price_period' => isset($request->shop_price_period) ? $request->shop_price_period : null,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'shop_renew') {

            return [
                'country' => $gioInfo['country'],
                'data' => $request->all(),
                'has_business' => isset($request->has_business) ? 1 : 0,
                'total_renew_shop' => isset($request->shop_expire_date_history_ids) ? count($request->shop_expire_date_history_ids) : 0,
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        } elseif ($detailsType == 'add_business') {

            return [
                'country' => $gioInfo['country'],
                'data' => $request->all(),
                'net_total' => isset($request->net_total) ? $request->net_total : 0,
                'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : 0,
                'discount_percent' => isset($request->discount_percent) ? $request->discount_percent : 0,
                'discount' => isset($request->discount) ? $request->discount : 0,
                'total_amount' => isset($request->total_payable) ? $request->total_payable : 0,
            ];
        }
    }

    public function updateDueTransactionStatus(object $request, ?object $dueSubscriptionTransaction): void
    {
        if (isset($dueSubscriptionTransaction)) {

            $dueSubscriptionTransaction->payment_status = $request->payment_status;
            $dueSubscriptionTransaction->payment_date = Carbon::now();
            $dueSubscriptionTransaction->paid = $dueSubscriptionTransaction->total_payable_amount;
            $dueSubscriptionTransaction->due = 0;
            $dueSubscriptionTransaction->payment_method_name = $request->payment_method_name;
            $dueSubscriptionTransaction->payment_trans_id = $request->payment_trans_id;
            $dueSubscriptionTransaction->save();
        }
    }

    public function subscriptionTransactions(?array $with = null): ?object
    {
        $query = UserSubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }
}
