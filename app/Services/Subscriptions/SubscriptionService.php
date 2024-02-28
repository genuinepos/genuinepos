<?php

namespace App\Services\Subscriptions;

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
}
