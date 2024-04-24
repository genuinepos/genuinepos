<?php

namespace App\Http\Controllers\Setups;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SAAS\Services\TenantServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class DueRepaymentController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
    ) {
    }

    public function index()
    {
        $dueSubscriptionTransaction = $this->subscriptionTransactionService->subscriptionTransactions(with: ['plan'])->where('due', '>', 0)->first();
        return view('setups.billing.due_repayment.index', compact('dueSubscriptionTransaction'));
    }
}
