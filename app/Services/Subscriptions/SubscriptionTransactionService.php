<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Enums\SubscriptionTransactionDetailsType;
use App\Models\Subscriptions\SubscriptionTransaction;

class SubscriptionTransactionService
{
    public function subscriptionTransactions(array $with = null)
    {
        $query = SubscriptionTransaction::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function addSubscriptionTransaction(object $request, object $subscription, bool $isTrialPlan, int $transactionType, ?object $plan): void
    {
        $transaction = new SubscriptionTransaction();
        $transaction->transaction_type = $transactionType;
        $transaction->subscription_id = $subscription->id;
        $transaction->plan_id = $subscription->plan_id;
        $transaction->payment_method_name = 'Cash-On-Delivery';
        $transaction->payment_trans_id = 'N/A';
        $transaction->net_total = $request->net_total;
        $transaction->discount = $request->discount;
        $transaction->total_payable_amount = $request->total_payable;
        $transaction->paid = $request->total_payable;
        $transaction->payment_status = BooleanType::True->value;
        $transaction->payment_date = Carbon::now();

        $transaction->details_type = SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value;

        $transactionDetails = $this->transactionDetails(request: $request, detailsType: SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value, plan: $plan);

        $transaction->details = $transactionDetails;

        $transaction->save();
    }

    function transactionDetails(object $request, string $detailsType, ?object $plan)
    {
        if ($detailsType == 'upgrade_plan_from_trial') {

            $countBusiness = isset($request->has_business) ? 1 : 0;
            $shopPlusBusiness = $request->shop_count + $countBusiness;
            $adjustableShopPrice = $request->total_payable / $shopPlusBusiness;

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            $adjustableBusinessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
                $adjustableBusinessPrice = $request->total_payable / $shopPlusBusiness;
            }

            return [
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'adjustable_business_price' => $adjustableBusinessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => $request->plan_price,
                'adjustable_shop_price' => $adjustableShopPrice,
                'shop_subtotal' => $request->shop_subtotal,
                'net_total' => $request->net_total,
                'discount' => $request->discount,
                'total_amount' => $request->total_payable,
            ];
        } elseif ($detailsType == 'direct_buy_plan') {

            $countBusiness = isset($request->has_business) ? 1 : 0;
            $shopPlusBusiness = $request->shop_count + $countBusiness;
            $adjustableShopPrice = $request->total_payable / $shopPlusBusiness;

            $businessPricePeriodCount = null;
            $businessPrice = 0;
            $adjustableBusinessPrice = 0;
            if (isset($request->has_business)) {

                $businessPricePeriodCount = $request->business_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->business_price_period_count;
                $businessPrice = $request->business_price ? $request->business_price : 0;
                $adjustableBusinessPrice = $request->total_payable / $shopPlusBusiness;
            }

            return [
                'has_business' => isset($request->has_business) ? 1 : 0,
                'business_price_period' => isset($request->has_business) ? $request->business_price_period : null,
                'business_price_period_count' => $businessPricePeriodCount,
                'business_price' => $businessPrice,
                'adjustable_business_price' => $adjustableBusinessPrice,
                'business_subtotal' => isset($request->has_business) ? $request->business_subtotal : 0,
                'shop_count' => $request->shop_count,
                'shop_price_period' => $request->shop_price_period,
                'shop_price_period_count' => $request->shop_price_period == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_count,
                'shop_price' => $request->plan_price,
                'adjustable_shop_price' => $adjustableShopPrice,
                'shop_subtotal' => $request->shop_subtotal,
                'net_total' => $request->net_total,
                'discount' => $request->discount,
                'total_amount' => $request->total_payable,
            ];
        } elseif ($detailsType == 'upgrade_plan_from_real_plan') {

            return [
                'net_total' => $request->net_total,
                'total_adjusted_amount' => $request->total_adjusted_amount,
                'discount' => $request->discount,
                'total_amount' => $request->total_payable,
            ];
        }
    }
}
