<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use Illuminate\Support\Facades\Artisan;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Jobs\AddShopMailJobQueue;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class AddShopController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function cart($tenantId)
    {
        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: [
            'user:id,tenant_id,name',
            'user.userSubscription',
            'user.userSubscription.plan'
        ]);

        $plan = $tenant?->user?->userSubscription?->plan;

        return view('saas::tenants.add_shop.cart', compact('tenant', 'plan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function confirm($tenantId, Request $request)
    {
        try {
            DB::beginTransaction();

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription',  'user.userSubscription.plan']);

            $plan = $tenant?->user?->userSubscription?->plan;

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::AddShop->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::AddShop->value, transactionDetailsType: SubscriptionTransactionDetailsType::AddShop->value, plan: $plan);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }

        DB::statement('use ' . $tenant->tenancy_db_name);
        try {
            DB::beginTransaction();

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, subscriptionUpdateType: SubscriptionUpdateType::AddShop->value);

            if (isset($request->increase_shop_count)) {

                for ($i = 0; $i < $request->increase_shop_count; $i++) {

                    $planPrice = $request->plan_price;
                    $discountPercent = $request->discount_percent;
                    $discount = ($planPrice / 100) * $discountPercent;
                    $adjustablePrice = $planPrice - $discount;

                    $this->shopExpireDateHistoryService->addShopExpireDateHistory(shopPricePeriod: $request->shop_price_period, shopPricePeriodCount: $request->shop_price_period_count, plan: $plan, adjustablePrice: $adjustablePrice);
                }
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::AddShop->value, transactionDetailsType: SubscriptionTransactionDetailsType::AddShop->value, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
        DB::reconnect();

        Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

        if ($tenant?->user) {

            dispatch(new AddShopMailJobQueue(
                user: $tenant?->user,
                increasedShopCount: $request->increase_shop_count,
                pricePerShop: $request->shop_price,
                pricePeriod: $request->shop_price_period,
                pricePeriodCount: $request->shop_price_period_count,
                subtotal: $request->shop_subtotal,
                netTotalAmount: $request->net_total,
                discount: $request->discount,
                totalPayable: $request->total_payable,
            ));
        }

        return response()->json(__('Plan upgraded successfully'));
    }
}
