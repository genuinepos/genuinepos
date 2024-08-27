<?php

namespace App\Http\Controllers\Billing;

use App\Enums\PlanType;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Branches\BranchService;
use Illuminate\Support\Facades\Session;
use App\Enums\SubscriptionTransactionType;
use App\Services\Setups\UpgradePlanService;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Services\Setups\DeleteTrialPeriodDataService;
use App\Services\Subscriptions\ShopExpireDateHistoryService;
use App\Services\Subscriptions\SubscriptionMailService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class UpgradePlanController extends Controller
{
    public function __construct(
        private UpgradePlanService $upgradePlanService,
        private PlanServiceInterface $planServiceInterface,
        private CouponServiceInterface $couponServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private BranchService $branchService,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
        private DeleteTrialPeriodDataService $deleteTrialPeriodDataService,
        private SubscriptionMailService $subscriptionMailService,
    ) {
    }

    public function index()
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plans = $this->planServiceInterface->plans()
            ->where('plan_type', PlanType::Fixed->value)
            ->where('status', BooleanType::True->value)
            ->where('is_trial_plan', BooleanType::False->value)->get();
        DB::reconnect();

        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::True->value) {

            return view('billing.plan_upgrade.upgrade_plan_from_trial.index', compact('plans'));
        } else {

            return view('billing.plan_upgrade.upgrade_plan_from_real_plan.index', compact('plans'));
        }
    }

    public function cart($id, $pricePeriod = null)
    {
        abort_if(!auth()->user()->can('billing_upgrade_plan'), 403);

        $branches = null;
        $leftBranchExpireDateHistories = null;
        $prepareAmounts = null;
        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::False->value) {

            $branches = $this->branchService->branches(with: ['parentBranch', 'shopExpireDateHistory'])
                ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

            $leftBranchExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories()
                ->where('is_created', BooleanType::False->value)->get();
        }

        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $id);
        DB::reconnect();

        if (config('generalSettings')['subscription']->plan_type == PlanType::Custom->value) {

            return __('Custom Plan can not be upgrade');
        }

        $currentSubscription =  $this->subscriptionService->singleSubscription(with: ['plan']);
        DB::reconnect();

        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::False->value) {

            $prepareAmounts = $this->upgradePlanService->prepareAmounts(plan: $plan, branches: $branches, leftBranchExpireDateHistories: $leftBranchExpireDateHistories, currentSubscription: $currentSubscription);
        }

        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::True->value) {

            return view('billing.plan_upgrade.upgrade_plan_from_trial.cart', compact('plan', 'currentSubscription', 'pricePeriod'));
        } else {

            return view('billing.plan_upgrade.upgrade_plan_from_real_plan.cart', compact('plan', 'branches', 'leftBranchExpireDateHistories', 'currentSubscription', 'pricePeriod', 'prepareAmounts'));
        }
    }

    public function confirm(Request $request)
    {
        abort_if(!auth()->user()->can('billing_upgrade_plan'), 403);

        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $request->plan_id);
        DB::reconnect();

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $isTrialPlan = $generalSettings['subscription']->is_trial_plan;

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, isTrialPlan: $isTrialPlan);

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

            $transactionDetailsType = $isTrialPlan == BooleanType::True->value ? SubscriptionTransactionDetailsType::UpgradePlanFromTrial->value : SubscriptionTransactionDetailsType::UpgradePlanFromRealPlan->value;

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: $transactionDetailsType, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        DB::statement('use ' . env('DB_DATABASE'));
        try {
            DB::beginTransaction();

            $tenantId = tenant('id');

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, plan: $plan, isTrialPlan: $isTrialPlan);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: $transactionDetailsType, plan: $plan);
            }

            if (isset($request->coupon_code) && isset($request->coupon_id)) {

                $this->couponServiceInterface->increaseCouponNumberOfUsed(code: $request->coupon_code);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
        }
        DB::reconnect();

        if ($isTrialPlan == BooleanType::True->value) {

            $this->deleteTrialPeriodDataService->cleanDataFromDB();
            Session::forget('startupType');
        }

        if ($tenant?->user) {

            $this->subscriptionMailService->sendPlanUpgradeSuccessMain(user: $tenant->user, planName: $plan->name, data: $request->all(), isTrialPlan: $isTrialPlan);
        }

        return response()->json(__('Plan upgraded successfully'));
    }
}
