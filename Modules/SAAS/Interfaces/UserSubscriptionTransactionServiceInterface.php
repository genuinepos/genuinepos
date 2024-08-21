<?php

namespace Modules\SAAS\Interfaces;

interface UserSubscriptionTransactionServiceInterface
{
    /**
     * @return \Modules\SAAS\Services\UserSubscriptionTransactionService
     */

    public function subscriptionTransactionsTable(object $request, ?int $userId = null): object;
    public function userSubscriptionTransactions(array $with = null): object;
    public function addUserSubscriptionTransaction(object $request, object $userSubscription, int $transactionType, string $transactionDetailsType, ?object $plan = null): void;
    public function subscriptionTransactions(?array $with = null): ?object;
    public function singleUserSubscriptionTransaction(int $id, ?array $with = null): ?object;
    public function updateDueTransactionStatus(object $request, ?object $dueSubscriptionTransaction): void;
}
