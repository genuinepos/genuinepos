<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
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

    public function addSubscriptionTransaction(object $request, object $subscription, bool $isTrialPlan, int $transactionType): void
    {
        $transaction = new SubscriptionTransaction();
        $transaction->transaction_type = $transactionType;
        $transaction->subscription_id = $subscription->id;
        $transaction->plan_id = $subscription->plan_id;
        $transaction->increase_shop_count = $isTrialPlan == BooleanType::True->value ? $subscription->initial_shop_count : 0;
        $transaction->payment_method_name = 'Cash-On-Delivery';
        $transaction->payment_trans_id = 'N/A';
        $transaction->subtotal = $isTrialPlan == BooleanType::True->value ? $subscription->initial_subtotal : $request->subtotal;
        $transaction->discount = $isTrialPlan == BooleanType::True->value ? $subscription->initial_discount : $request->discount;
        $transaction->total_payable_amount = $isTrialPlan == BooleanType::True->value ? $subscription->initial_total_payable_amount : $request->total_payable;
        $transaction->paid = $isTrialPlan == BooleanType::True->value ? $subscription->initial_total_payable_amount : $request->total_payable;
        $transaction->payment_status = BooleanType::True->value;
        $transaction->payment_date = Carbon::now();
        $transaction->save();
    }
}
