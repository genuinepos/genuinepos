<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use App\Enums\SubscriptionTransactionType;
use App\Enums\SubscriptionTransactionDetailsType;
use App\Http\Requests\Setups\AddBusinessConfirmRequest;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Services\Subscriptions\SubscriptionMailService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class AddBusinessController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private CouponServiceInterface $couponServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionMailService $subscriptionMailService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
    ) {
    }

    public function cart()
    {
        abort_if(!auth()->user()->can('billing_business_add'), 403);
        abort_if(config('generalSettings')['subscription']->has_business == BooleanType::True->value, 403);

        $planId = config('generalSettings')['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

        return view('setups.billing.add_business.cart', compact('plan'));
    }

    public function confirm(AddBusinessConfirmRequest $request)
    {
        $planId = config('generalSettings')['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

        try {
            DB::beginTransaction();

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, subscriptionUpdateType: SubscriptionUpdateType::AddBusiness->value);

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::AddBusiness->value, transactionDetailsType: SubscriptionTransactionDetailsType::AddBusiness->value, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        DB::statement('use ' . env('DB_DATABASE'));
        try {
            DB::beginTransaction();

            $tenantId = tenant('id');

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::AddBusiness->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::AddBusiness->value, transactionDetailsType: SubscriptionTransactionDetailsType::AddBusiness->value, plan: $plan);
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

            $this->subscriptionMailService->sendSubscriptionAddBusinessInvoiceMail(
                user: $tenant?->user,
                data: $request->all()
            );
        }

        if (auth()->user()->can('has_access_to_all_area')) {

            auth()->user()->branch_id = null;
            auth()->user()->is_belonging_an_area = BooleanType::False->value;
            auth()->user()->save();
        }

        return response()->json(__('Shop/Business renewed successfully'));
    }
}
