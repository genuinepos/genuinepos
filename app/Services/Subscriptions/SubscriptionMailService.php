<?php

namespace App\Services\Subscriptions;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Models\Subscriptions\Subscription;
use App\Jobs\SendSubscriptionPlanUpgradeMailQueueJob;

class SubscriptionMailService
{
    function sendPlanUpgradeSuccessMain(object $user): void
    {
        dispatch(new SendSubscriptionPlanUpgradeMailQueueJob(to: $user->email, user: $user));

        if ($user->id != 1) {

            $superadmin = (new App\Services\Users\UserService())->singleUser(id: 1);
            dispatch(new SendSubscriptionPlanUpgradeMailQueueJob(to: $superadmin->email, user: $superadmin));
        }
    }
}
