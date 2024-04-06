<?php

namespace Modules\SAAS\Interfaces;

interface UserSubscriptionTransactionServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\UserSubscriptionTransactionService
     */

    public function userSubscriptionTransactions(array $with = null): object;
    public function addUserSubscriptionTransaction(object $request, object $userSubscription, int $transactionType, string $transactionDetailsType, ?object $plan = null): void;
}
