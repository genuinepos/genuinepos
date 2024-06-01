<?php

namespace App\Http\Controllers\Billing;

use App\Enums\BooleanType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use App\Enums\SubscriptionTransactionType;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use App\Http\Requests\Billing\AddShopConfirmRequest;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Services\Setups\ShopExpireDateHistoryService;
use App\Services\Subscriptions\SubscriptionMailService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class AddShopController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private CouponServiceInterface $couponServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
        private SubscriptionMailService $subscriptionMailService,
    ) {
    }

    public function cart()
    {
        abort_if(!auth()->user()->can('billing_branch_add') && config('generalSettings')['subscription']->has_due_amount == BooleanType::True->value, 403);

        $planId = config('generalSettings')['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

        return view('billing.add_shop.cart', compact('plan'));
    }

    public function confirm(AddShopConfirmRequest $request)
    {
        $generalSettings = config('generalSettings');
        $planId = $generalSettings['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

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

        DB::statement('use ' . env('DB_DATABASE'));
        try {
            DB::beginTransaction();

            $tenantId = tenant('id');

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::AddShop->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::AddShop->value, transactionDetailsType: SubscriptionTransactionDetailsType::AddShop->value, plan: $plan);
            }

            if (isset($request->coupon_code) && isset($request->coupon_id)) {

                $this->couponServiceInterface->increaseCouponNumberOfUsed(code: $request->coupon_code);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }
        DB::reconnect();

        if ($tenant?->user) {

            $this->subscriptionMailService->sendSubscriptionAddShopInvoiceMail(
                user: $tenant?->user,
                increasedShopCount: $request->increase_shop_count,
                pricePerShop: $request->shop_price,
                pricePeriod: $request->shop_price_period,
                pricePeriodCount: $request->shop_price_period_count,
                subtotal: $request->shop_subtotal,
                netTotalAmount: $request->net_total,
                discount: $request->discount,
                totalPayable: $request->total_payable,
            );
        }

        return response()->json(__('Plan upgraded successfully'));
    }
}
