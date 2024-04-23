<?php

namespace App\Http\Controllers\Setups;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use App\Services\Setups\BranchService;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Utils\ExpireDateAllocation;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Http\Requests\Setups\ShopRenewConfirmRequest;
use App\Services\Setups\ShopExpireDateHistoryService;
use App\Services\Subscriptions\SubscriptionMailService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class ShopRenewController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private CouponServiceInterface $couponServiceInterface,
        private BranchService $branchService,
        private SubscriptionService $subscriptionService,
        private SubscriptionMailService $subscriptionMailService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private TenantServiceInterface $tenantServiceInterface,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
    ) {
    }

    public function cart()
    {
        abort_if(!auth()->user()->can('billing_renew_branch'), 403);

        $branches = $this->branchService->branches(with: ['parentBranch', 'shopExpireDateHistory'])
            ->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();

        $leftBranchExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories()
            ->where('is_created', BooleanType::False->value)->get();

        $planId = config('generalSettings')['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

        $currentSubscription =  $this->subscriptionService->singleSubscription(with: ['plan']);

        return view('setups.billing.shop_renew.cart', compact('branches', 'leftBranchExpireDateHistories', 'plan', 'currentSubscription'));
    }

    public function confirm(ShopRenewConfirmRequest $request)
    {
        // return $request->all();
        $planId = config('generalSettings')['subscription']->plan_id;
        DB::statement('use ' . env('DB_DATABASE'));
        $plan = $this->planServiceInterface->singlePlanById(id: $planId);
        DB::reconnect();

        try {
            DB::beginTransaction();

            $updateSubscription = $this->subscriptionService->updateSubscription(request: $request, plan: $plan, subscriptionUpdateType: SubscriptionUpdateType::ShopRenew->value);

            if (isset($request->shop_expire_date_history_ids) && count($request->shop_expire_date_history_ids) > 0) {

                foreach ($request->shop_expire_date_history_ids as $index => $shopExpireDateHistoryId) {

                    $shopExpireDateHistory = $this->shopExpireDateHistoryService->singleShopExpireDateHistory(id: $shopExpireDateHistoryId);

                    $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
                    $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_prices[$index]);
                    $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
                    $adjustablePrice = $shopPriceInUsd - $discountAmount;

                    $startDate = date('Y-m-d') <= $shopExpireDateHistory->expire_date ? $shopExpireDateHistory->expire_date : null;
                    $expireDate = ExpireDateAllocation::getExpireDate(period: $request->shop_price_periods[$index] == 'lifetime' ? 'year' : $request->shop_price_periods[$index], periodCount: $request->shop_price_periods[$index] == 'lifetime' ? $plan->applicable_lifetime_years : $request->shop_price_period_counts[$index], startDate: $startDate);

                    $this->shopExpireDateHistoryService->updateShopExpireDateHistory(id: $shopExpireDateHistoryId, shopPricePeriod: $request->shop_price_periods[$index], adjustablePrice: $adjustablePrice, expireDate: $expireDate);
                }
            }

            $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $updateSubscription, transactionType: SubscriptionTransactionType::ShopRenew->value, transactionDetailsType: SubscriptionTransactionDetailsType::ShopRenew->value, plan: $plan);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        DB::statement('use ' . env('DB_DATABASE'));
        try {
            DB::beginTransaction();

            $tenantId = tenant('id');

            $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);

            $updateUserSubscription = $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription?->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::ShopRenew->value);

            if (isset($updateUserSubscription)) {

                $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $updateUserSubscription, transactionType: SubscriptionTransactionType::ShopRenew->value, transactionDetailsType: SubscriptionTransactionDetailsType::ShopRenew->value, plan: $plan);
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

            $this->subscriptionMailService->sendSubscriptionShopRenewInvoiceMail(
                user: $tenant?->user,
                data: $request->all()
            );
        }
        
        return response()->json(__('Shop/Business renewed successfully'));
    }
}
