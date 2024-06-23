<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\SubscriptionUpdateType;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Services\UpdateExpireDateService;
use App\Services\Subscriptions\SubscriptionService;
use App\Services\Setups\ShopExpireDateHistoryService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use Modules\SAAS\Http\Requests\UpdateExpireDateConfirmRequest;

class UpdateExpireDateController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private SubscriptionService $subscriptionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
        private UpdateExpireDateService $updateExpireDateService,
    ) {
    }

    public function index($tenantId)
    {
        abort_if(!auth()->user()->can('tenants_update_expire_date'), 403);

        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user']);

        DB::statement('use ' . $tenant->tenancy_db_name);
        $currentSubscription = $this->subscriptionService->singleSubscription();
        $shopExpireDateHistories = $this->shopExpireDateHistoryService->shopExpireDateHistories(with: ['branch', 'branch.parentBranch'])->get();
        DB::reconnect();

        return view('saas::tenants.update_expire_date.index', compact('tenant', 'currentSubscription', 'shopExpireDateHistories'));
    }

    public function confirm($tenantId, UpdateExpireDateConfirmRequest $request)
    {
        $restrictions = $this->updateExpireDateService->restrictions(request: $request);
        if ($restrictions['pass'] == false) {

            return response()->json(['errorMsg' => $restrictions['msg']]);
        }

        $tenant = $this->tenantServiceInterface->singleTenant(id: $tenantId, with: ['user', 'user.userSubscription']);
        $this->userSubscriptionServiceInterface->updateUserSubscription(id: $tenant?->user?->userSubscription->id, request: $request, subscriptionUpdateType: SubscriptionUpdateType::UpdateExpireDate->value);

        DB::statement('use ' . $tenant->tenancy_db_name);

        $this->subscriptionService->updateSubscription(request: $request, subscriptionUpdateType: SubscriptionUpdateType::UpdateExpireDate->value, tenantId: $tenant->id);

        foreach ($request->shop_expire_date_history_ids as $index => $shopExpireDateHistoryId) {

            $this->shopExpireDateHistoryService->updateShopExpireDateHistory(id: $shopExpireDateHistoryId, expireDate: $request->shop_new_expire_dates[$index]);
        }
        DB::reconnect();

        return response()->json(__('Expire dates is updated successfully.'));
    }
}
