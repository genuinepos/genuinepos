<?php

namespace Modules\SAAS\Http\Controllers;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\SubscriptionService;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UpdatePaymentStatusController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
    ) {}

    public function index($tenantId)
    {
        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);
        $dueTransaction = $this->userSubscriptionTransactionServiceInterface->subscriptionTransactions()->where('user_subscription_id', $tenant?->user?->userSubscription?->id)->where('due', '>', 0)->first();

        return view('saas::tenants.update_payment_status.index', compact('tenant', 'dueTransaction'));
    }

    public function update($tenantId, Request $request)
    {
        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: [
            'user',
            'user.userSubscription',
            'user.userSubscription.dueSubscriptionTransaction'
        ]);

        $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::UpdatePaymentStatus->value);

        if ($request->payment_status == BooleanType::True->value && $tenant?->user?->userSubscription?->dueSubscriptionTransaction) {

            $this->userSubscriptionTransactionServiceInterface->updateDueTransactionStatus(request: $request, dueSubscriptionTransaction: $tenant?->user?->userSubscription?->dueSubscriptionTransaction);
        }

        DB::statement('use ' . $tenant->tenancy_db_name);

        $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, subscriptionUpdateType: SubscriptionUpdateType::UpdatePaymentStatus->value);

        if ($request->payment_status == BooleanType::True->value && $updateSubscription?->dueSubscriptionTransaction) {

            if ($request->discount_percent != $updateSubscription?->dueSubscriptionTransaction?->discount_percent) {

                $shopExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories()->get();

                $shopPrice = $updateSubscription?->dueSubscriptionTransaction?->details?->shop_price ? $updateSubscription?->dueSubscriptionTransaction?->details?->shop_price : 0;

                $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
                $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($shopPrice);
                $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
                $adjustablePrice = round($shopPriceInUsd - $discountAmount, 0);

                foreach ($shopExpireDateHistories as $shopExpireDateHistory) {

                    $this->shopExpireDateHistoryService->updateShopExpireDateHistory(id: $shopExpireDateHistory->id, adjustablePrice: $adjustablePrice);
                }
            }

            $this->subscriptionTransactionService->updateDueTransactionStatus(request: $request, dueSubscriptionTransaction: $updateSubscription?->dueSubscriptionTransaction);
        }

        DB::reconnect();

        Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

        return response()->json(__('Payment Status is updated successfully.'));
    }
}
