<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Utils\ExpireDateCalculation;
use Illuminate\Support\Facades\Session;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use App\Services\Setups\DeleteTrialPeriodDataService;
use App\Services\Setups\ShopExpireDateHistoryService;
use App\Services\Subscriptions\SubscriptionMailService;
use App\Services\Subscriptions\SubscriptionTransactionService;

class UpgradePlanController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
        private DeleteTrialPeriodDataService $deleteTrialPeriodDataService,
        private SubscriptionMailService $subscriptionMailService,
        private ExpireDateCalculation $expireDateCalculation,
    ) {
    }

    public function index()
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plans = $this->planServiceInterface->plans(with: ['currency:id,symbol'])
            ->active()->where('is_trial_plan', BooleanType::False->value)->get();
        DB::reconnect();

        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::True->value) {

            return view('setups.billing.plan_upgrade.upgrade_plan_from_trial.index', compact('plans'));
        } else {

            return view('setups.billing.plan_upgrade.upgrade_plan_from_real_plan.index', compact('plans'));
        }
    }

    public function cart($id, $pricePeriod, Request $request)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $id, with: ['currency:id,symbol']);
        DB::reconnect();

        $currentSubscription =  $this->subscriptionService->singleSubscription(with: ['plan']);

        if (config('generalSettings')['subscription']->is_trial_plan == BooleanType::True->value) {

            return view('setups.billing.plan_upgrade.upgrade_plan_from_trial.cart', compact('plan', 'currentSubscription', 'pricePeriod'));
        } else {

            return view('setups.billing.plan_upgrade.upgrade_plan_from_real_plan.cart', compact('plan', 'currentSubscription', 'pricePeriod'));
        }
    }

    public function confirm(Request $request)
    {
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $request->plan_id);
        DB::reconnect();

        try {
            DB::beginTransaction();

            $generalSettings = config('generalSettings');
            $isTrialPlan = $generalSettings['subscription']->is_trial_plan;

            $subscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, isTrialPlan: $isTrialPlan, expireDateCalculation: $this->expireDateCalculation);

            if ($isTrialPlan == BooleanType::True->value) {

                $this->shopExpireDateHistoryService->addShopExpireDateHistory(request: $request, expireDateCalculation: $this->expireDateCalculation);
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $subscription, isTrialPlan: $isTrialPlan, transactionType: SubscriptionTransactionType::UpgradePlan->value);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        if ($isTrialPlan == BooleanType::True->value) {

            $this->deleteTrialPeriodDataService->cleanDataFromDB();
            Session::forget('startupType');
        }

        $this->subscriptionMailService->sendPlanUpgradeSuccessMain(user: auth()->user());

        return response()->json(__('Plan upgraded successfully'));
    }
}
