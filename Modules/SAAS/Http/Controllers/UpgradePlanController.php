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
use App\Services\Branches\BranchService;
use App\Enums\SubscriptionTransactionType;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\UpgradePlanService;
use Modules\SAAS\Jobs\SendUpgradePlanMailJobQueue;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use Modules\SAAS\Jobs\SendNewSubscriptionMailQueueJob;
use Modules\SAAS\Http\Requests\UpgradePlanConfirmRequest;
use App\Services\Subscriptions\DeleteTrialPeriodDataService;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
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
        private UpgradePlanService $upgradePlanService,
        private BranchService $branchService,
    ) {}

    public function index($tenantId)
    {
        abort_if(!auth()->user()->can('tenants_upgrade_plan'), 403);

        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: [
            'user:id,tenant_id,name',
            'user.userSubscription',
            'user.userSubscription.plan'
        ]);

        if ($tenant?->user?->userSubscription?->plan?->is_trial_plan == BooleanType::True->value) {

            return redirect()->route('saas.tenants.upgrade.plan.cart', ['tenantId' => $tenantId]);
            // return view('saas::tenants.upgrade_plan.upgrade_plan_from_trial.cart', compact('plans', 'tenant', 'tenantId'));
        } else {

            $plans = $this->planServiceInterface->plans()->where('is_trial_plan', BooleanType::False->value)
                ->where('status', BooleanType::True->value)
                ->orderBy('price_per_month', 'asc')
                ->orderBy('price_per_year', 'asc')
                ->orderBy('lifetime_price', 'asc')
                ->get();

            return view('saas::tenants.upgrade_plan.upgrade_plan_from_real_plan.plan_list', compact('plans', 'tenant', 'tenantId'));
        }
    }

    public function cart($tenantId, $planId = null, $pricePeriod = null)
    {
        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: [
            'user:id,tenant_id,name',
            'user.userSubscription',
            'user.userSubscription.plan'
        ]);

        if ($tenant?->user?->userSubscription?->plan?->is_trial_plan == BooleanType::True->value) {

            $plans = $this->planServiceInterface->plans()->where('is_trial_plan', BooleanType::False->value)
                ->where('status', BooleanType::True->value)
                ->orderBy('price_per_month', 'asc')
                ->orderBy('price_per_year', 'asc')
                ->orderBy('lifetime_price', 'asc')
                ->get();

            return view('saas::tenants.upgrade_plan.upgrade_plan_from_trial.cart', compact('plans', 'tenant', 'tenantId'));
        } else {

            $plan = $this->planServiceInterface->singlePlanById(id: $planId);

            DB::statement('use ' . $tenant->tenancy_db_name);
            $branches = null;
            $leftBranchExpireDateHistories = null;
            $prepareAmounts = null;

            $branches = $this->branchService->branches(with: ['parentBranch', 'shopExpireDateHistory'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

            $leftBranchExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories()
                ->where('is_created', BooleanType::False->value)->get();

            $currentSubscription =  $this->subscriptionService->singleSubscription(with: ['plan']);

            $prepareAmounts = $this->upgradePlanService->prepareAmounts(plan: $plan, branches: $branches, leftBranchExpireDateHistories: $leftBranchExpireDateHistories, currentSubscription: $currentSubscription);
            DB::reconnect();

            return view('saas::tenants.upgrade_plan.upgrade_plan_from_real_plan.cart', compact('tenant', 'plan', 'branches', 'leftBranchExpireDateHistories', 'currentSubscription', 'pricePeriod', 'prepareAmounts'));
        }
    }

    public function confirm($tenantId, UpgradePlanConfirmRequest $request)
    {
        $plan = $this->planServiceInterface->singlePlanById(id: $request->plan_id);

        try {
            DB::beginTransaction();

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription', 'user.userSubscription.plan']);

            $isTrialPlan = $tenant?->user?->userSubscription?->plan?->is_trial_plan;

            $transactionDetailsType = $isTrialPlan == BooleanType::True->value ? SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value : SubscriptionTransactionDetailsType::UpgradePlanFromRealPlan->value;

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, plan: $plan, isTrialPlan: $isTrialPlan);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: $transactionDetailsType, plan: $plan);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }

        DB::statement('use ' . $tenant->tenancy_db_name);
        try {
            DB::beginTransaction();

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, isTrialPlan: $isTrialPlan);

            // $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
            // $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_price);
            // $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
            // $adjustablePrice = $shopPriceInUsd - $discountAmount;

            // for ($i = 0; $i < $request->shop_count; $i++) {

            //     $this->shopExpireDateHistoryService->addShopExpireDateHistory(shopPricePeriod: $request->shop_price_period, shopPricePeriodCount: $request->shop_price_period_count, plan: $plan, adjustablePrice: $adjustablePrice);
            // }

            if ($isTrialPlan == BooleanType::True->value) {

                $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
                $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_price);
                $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
                $adjustablePrice = $shopPriceInUsd - $discountAmount;

                for ($i = 0; $i < $request->shop_count; $i++) {

                    $this->shopExpireDateHistoryService->addShopExpireDateHistory(shopPricePeriod: $request->shop_price_period, shopPricePeriodCount: $request->shop_price_period_count, plan: $plan, adjustablePrice: $adjustablePrice);
                }
            } else {

                if (isset($request->shop_expire_date_history_ids)) {

                    foreach ($request->shop_expire_date_history_ids as $shopExpireDateHistoryId) {

                        $this->shopExpireDateHistoryService->updateShopExpireDateHistoryAdjustablePriceAndPricePeriod(plan: $plan, shopExpireDateHistoryId: $shopExpireDateHistoryId, discountPercent: $request->discount_percent);
                    }
                }
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: $transactionDetailsType, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        if ($isTrialPlan == BooleanType::True->value) {

            $this->deleteTrialPeriodDataService->cleanDataFromDB();
            Session::forget('startupType');
        }

        DB::reconnect();
        Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

        if ($tenant?->user) {

            $appUrl = UrlGenerator::generateFullUrlFromDomain($tenantId);
            dispatch(new SendUpgradePlanMailJobQueue(user: $tenant?->user, data: $request->all(), planName: $plan->name, isTrialPlan: $isTrialPlan, appUrl: $appUrl));
        }

        return response()->json(__('Plan upgraded successfully'));
    }
}
