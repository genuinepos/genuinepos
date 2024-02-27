<?php

namespace App\Services\Subscriptions;

use App\Models\Subscriptions\Subscription;

class SubscriptionService
{
    public function updateBusinessStartUpCompletingStatus() {

        $subscription = Subscription::first();
        $subscription->is_completed_business_startup = 1;
        $subscription->save();
    }

    public function updateBranchStartUpCompletingStatus() {

        $subscription = Subscription::first();
        $subscription->is_completed_branch_startup = 1;
        $subscription->save();
    }
}
