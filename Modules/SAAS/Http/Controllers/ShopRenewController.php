<?php

namespace Modules\SAAS\Http\Controllers;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use Illuminate\Http\RedirectResponse;
use App\Services\Branches\BranchService;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Jobs\ShopRenewMailJobQueue;
use Modules\SAAS\Utils\ExpireDateAllocation;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Http\Requests\ShopRenewConfirmRequest;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class ShopRenewController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private BranchService $branchService,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
    ) {}

    public function cart($tenantId)
    {
        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: [
            'user:id,tenant_id,name',
            'user.userSubscription',
            'user.userSubscription.plan'
        ]);

        $plan = $tenant?->user?->userSubscription?->plan;

        $planId = $plan->id;

        DB::statement('use ' . $tenant->tenancy_db_name);

        $business = DB::table('general_settings')->where('key', 'business_or_shop__business_name')->select('value')->first();

        $branches = $this->branchService->branches(with: ['parentBranch', 'shopExpireDateHistory'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $leftBranchExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories()
            ->where('is_created', BooleanType::False->value)->get();

        $currentSubscription = $this->subscriptionService->singleSubscription(with: ['plan']);
        DB::reconnect();

        return view('saas::tenants.shop_renew.cart', compact('tenant', 'business', 'branches', 'leftBranchExpireDateHistories', 'plan', 'currentSubscription'));
    }

    public function confirm(ShopRenewConfirmRequest $request, $tenantId)
    {
        try {
            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription',  'user.userSubscription.plan']);

            if (!isset($tenant)) {

                return response()->json(['errorMsg' => 'Tenant not found.']);
            }

            $plan = $tenant?->user?->userSubscription?->plan;

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, plan: $plan, subscriptionUpdateType: SubscriptionUpdateType::ShopRenew->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::ShopRenew->value, transactionDetailsType: SubscriptionTransactionDetailsType::ShopRenew->value, plan: $plan);
            }

            DB::connection('mysql')->statement('use ' . $tenant->tenancy_db_name);

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, subscriptionUpdateType: SubscriptionUpdateType::ShopRenew->value);

            if (isset($request->shop_expire_date_history_ids) && count($request->shop_expire_date_history_ids) > 0) {

                foreach ($request->shop_expire_date_history_ids as $index => $shopExpireDateHistoryId) {

                    $shopExpireDateHistory = $this->shopExpireDateHistoryService->singleShopExpireDateHistory(id: $shopExpireDateHistoryId);

                    $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
                    $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_prices[$index]);
                    $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
                    $adjustablePrice = $shopPriceInUsd - $discountAmount;

                    $startDate = date('Y-m-d') <= $shopExpireDateHistory->main_expire_date ? $shopExpireDateHistory->main_expire_date : null;
                    $expireDate = ExpireDateAllocation::getExpireDate(period: $request->shop_price_periods[$index] == 'lifetime' ? 'year' : $request->shop_price_periods[$index], periodCount: $request->shop_price_periods[$index] == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_counts[$index], startDate: $startDate);

                    $this->shopExpireDateHistoryService->updateShopExpireDateHistory(id: $shopExpireDateHistoryId, shopPricePeriod: $request->shop_price_periods[$index], adjustablePrice: $adjustablePrice, expireDate: $expireDate, mainExpireDate: $expireDate);
                }
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::ShopRenew->value, transactionDetailsType: SubscriptionTransactionDetailsType::ShopRenew->value, plan: $plan);

            DB::reconnect();

            if ($tenant?->user) {

                dispatch(new ShopRenewMailJobQueue(user: $tenant?->user, data: $request->all()));
            }
        } catch (\Exception $e) {

            Log::debug($e->getMessage());
            Log::info($e->getMessage());
            return null;
        }

        return response()->json(__('Store/company renewed successfully'));
    }
}
