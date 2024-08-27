<?php

namespace Modules\SAAS\Http\Controllers;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\SAAS\Utils\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Enums\SubscriptionTransactionType;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use Modules\SAAS\Jobs\SendUpgradePlanMailJobQueue;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Services\Setups\DeleteTrialPeriodDataService;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
use Modules\SAAS\Jobs\SendNewSubscriptionMailQueueJob;
use Modules\SAAS\Http\Requests\UpgradePlanConfirmRequest;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UpgradePlanController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
        private CouponServiceInterface $couponServiceInterface,
        private DeleteTrialPeriodDataService $deleteTrialPeriodDataService,
    ) {
    }

    public function cart($tenantId)
    {
        abort_if(!auth()->user()->can('tenants_upgrade_plan'), 403);

        $plans = $this->planServiceInterface->plans()->where('is_trial_plan', BooleanType::False->value)
            ->where('status', BooleanType::True->value)->get();

        return view('saas::tenants.upgrade_plan.cart', compact('plans', 'tenantId'));
    }

    public function confirm($tenantId, UpgradePlanConfirmRequest $request)
    {
        $plan = $this->planServiceInterface->singlePlanById(id: $request->plan_id);

        try {
            DB::beginTransaction();

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, plan: $plan, isTrialPlan: BooleanType::True->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value, plan: $plan);
            }

            if (isset($request->coupon_code) && isset($request->coupon_id)) {

                $this->couponServiceInterface->increaseCouponNumberOfUsed(code: $request->coupon_code);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }

        DB::statement('use ' . $tenant->tenancy_db_name);
        try {
            DB::beginTransaction();

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, isTrialPlan: BooleanType::True->value);

            $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
            $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_price);
            $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
            $adjustablePrice = $shopPriceInUsd - $discountAmount;

            for ($i = 0; $i < $request->shop_count; $i++) {

                $this->shopExpireDateHistoryService->addShopExpireDateHistory(shopPricePeriod: $request->shop_price_period, shopPricePeriodCount: $request->shop_price_period_count, plan: $plan, adjustablePrice: $adjustablePrice);
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        $this->deleteTrialPeriodDataService->cleanDataFromDB();
        Session::forget('startupType');

        DB::reconnect();
        Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

        if ($tenant?->user) {

            $appUrl = UrlGenerator::generateFullUrlFromDomain($tenantId);
            dispatch(new SendUpgradePlanMailJobQueue(user: $tenant?->user, data: $request->all(), planName: $plan->name, appUrl: $appUrl));
        }

        return response()->json(__('Plan upgraded successfully'));
    }
}
