<?php

namespace App\Services\Subscriptions;

use App\Models\Subscriptions\Subscription;

class SubscriptionService
{
    public function updateStartCompletingStatus() {

        $subscription = Subscription::first();
        $subscription->is_completed_startup = 1;
        $subscription->save();
    }
}
