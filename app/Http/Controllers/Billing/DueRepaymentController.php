<?php

namespace App\Http\Controllers\Billing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        abort_if(!auth()->user()->can('billing_pay_due_payment'), 403);
        $dueSubscriptionTransaction = $this->subscriptionTransactionService->subscriptionTransactions(with: ['plan'])->where('due', '>', 0)->first();
        DB::reconnect();
        return view('billing.due_repayment.index', compact('dueSubscriptionTransaction'));
    }
}
