<?php

namespace Modules\SAAS\Interfaces;

interface UserSubscriptionServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\UserSubscriptionService
     */

    public function addUserSubscription(object $request, int $subscriptionUserId, object $plan): object;
    public function updateUserSubscription(?int $id, object $request, ?object $plan = null, int $isTrialPlan = 0, int $subscriptionUpdateType = 1): ?object;
}
