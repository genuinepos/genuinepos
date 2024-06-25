<?php

namespace Modules\SAAS\Services;

use Carbon\Carbon;
use App\Enums\BooleanType;
use App\Mail\NewSubscriptionMail;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\SAAS\Utils\UrlGenerator;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;
use App\Enums\SubscriptionTransactionType;
use Modules\SAAS\Utils\ExpireDateAllocation;
use App\Services\GeneralSettingServiceInterface;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Interfaces\UserServiceInterface;
use Modules\SAAS\Utils\AmountInUsdIfLocationIsBd;
use App\Services\Subscriptions\SubscriptionService;
use Modules\SAAS\Interfaces\CouponServiceInterface;
use App\Services\Setups\ShopExpireDateHistoryService;
use Modules\SAAS\Interfaces\UserSubscriptionServiceInterface;
use App\Services\Subscriptions\SubscriptionTransactionService;
use Modules\SAAS\Interfaces\UserSubscriptionTransactionServiceInterface;

class TenantService implements TenantServiceInterface
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private UserServiceInterface $userServiceInterface,
        private GeneralSettingServiceInterface $generalSettingServiceInterface,
        private UserService $appUserService,
        private CouponServiceInterface $couponServiceInterface,
        private UserSubscriptionServiceInterface $userSubscriptionServiceInterface,
        private UserSubscriptionTransactionServiceInterface $userSubscriptionTransactionServiceInterface,
        private SubscriptionService $subscriptionService,
        private SubscriptionTransactionService $subscriptionTransactionService,
        private ShopExpireDateHistoryService $shopExpireDateHistoryService,
    ) {
    }

    public function addTenant(object $request): ?Tenant
    {
        try {
            $plan = $this->planServiceInterface->singlePlanById(id: $request->plan_id);

            $tenant = Tenant::create([
                'id' => $request->domain,
                'name' => $request->name,
                'impersonate_user' => 1,
                'user_id' => 1,
            ]);

            if (isset($tenant)) {

                $domain = $tenant->domains()->create(['domain' => $request->domain]);

                if ($domain) {

                    $addSubscriberUser = $this->userServiceInterface->addSubscriberUser(request: $request, tenantId: $tenant->id);
                    $tenant->update(['user_id' => $addSubscriberUser->id]);

                    $addUserSubscription = $this->userSubscriptionServiceInterface->addUserSubscription(request: $request, subscriberUserId: $addSubscriberUser->id, plan: $plan);

                    if ($plan->is_trial_plan == BooleanType::False->value) {

                        if (isset($request->coupon_code) && isset($request->coupon_id)) {

                            $this->couponServiceInterface->increaseCouponNumberOfUsed(code: $request->coupon_code);
                        }

                        $this->userSubscriptionTransactionServiceInterface->addUserSubscriptionTransaction(request: $request, userSubscription: $addUserSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: SubscriptionTransactionDetailsType::DirectBuyPlan->value, plan: $plan);
                    }

                    DB::statement('use ' . $tenant->tenancy_db_name);

                    $this->appUserService->addAppSuperAdminUser(request: $request);
                    // Insert settings coming from tenant creation form
                    $this->generalSettingServiceInterface->partiallyUpdateBusinessSettings(request: $request);

                    $addSubscription = $this->subscriptionService->addSubscription(request: $request, plan: $plan);

                    if ($plan->is_trial_plan == BooleanType::False->value) {

                        $this->subscriptionTransactionService->addSubscriptionTransaction(request: $request, subscription: $addSubscription, transactionType: SubscriptionTransactionType::BuyPlan->value, transactionDetailsType: SubscriptionTransactionDetailsType::DirectBuyPlan->value, plan: $plan);
                    }

                    if ($plan->is_trial_plan == BooleanType::False->value) {

                        $discountPercent = isset($request->discount_percent) ? $request->discount_percent : 0;
                        $shopPriceInUsd = AmountInUsdIfLocationIsBd::amountInUsd($request->shop_price);
                        $discountAmount = ($shopPriceInUsd / 100) * $discountPercent;
                        $adjustablePrice = round($shopPriceInUsd - $discountAmount, 0);

                        for ($i = 0; $i < $request->shop_count; $i++) {

                            $this->shopExpireDateHistoryService->addShopExpireDateHistory(shopPricePeriod: $request->shop_price_period, shopPricePeriodCount: $request->shop_price_period_count, plan: $plan, adjustablePrice: $adjustablePrice);
                        }
                    }

                    DB::reconnect();
                    // Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

                    $appUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);
                    if ($plan->is_trial_plan) {

                        $trialExpireDate = ExpireDateAllocation::getExpireDate(period: 'day', periodCount: $plan->trial_days);

                        dispatch(new \Modules\SAAS\Jobs\SendTrialMailJobQueue(data: $request->all(), appUrl: $appUrl, trialExpireDate: $trialExpireDate));
                    } else {

                        dispatch(new \Modules\SAAS\Jobs\SendNewSubscriptionMailQueueJob(data: $request->all(), planName: $plan->name, appUrl: $appUrl));
                    }
                }
            }
        } catch (\Exception $e) {

            Log::debug($e->getMessage());
            Log::info($e->getMessage());
            return null;
        }

        return $tenant;
    }

    public function tenantsTable(): object
    {
        $tenants = DB::table('tenants')
            ->leftJoin('domains', 'tenants.id', 'domains.tenant_id')
            ->leftJoin('users', 'tenants.id', 'users.tenant_id')
            ->leftJoin('user_subscriptions', 'users.id', 'user_subscriptions.user_id')
            ->leftJoin('plans', 'user_subscriptions.plan_id', 'plans.id')
            ->select(
                'tenants.id',
                'tenants.data',
                'tenants.created_at',
                'domains.domain',
                'users.name as user_name',
                'users.email',
                'users.phone',
                'user_subscriptions.trial_start_date',
                'user_subscriptions.initial_plan_start_date',
                'user_subscriptions.has_due_amount',
                'user_subscriptions.due_repayment_date',
                'user_subscriptions.has_business',
                'user_subscriptions.current_shop_count',
                'plans.name as plan_name',
                'plans.is_trial_plan',
                'plans.trial_days',
            );

        return DataTables::of($tenants)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                $domain = \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($row->domain);

                if (auth()->user()->can('tenants_show')) {

                    $html .= '<a href="' . route('saas.tenants.show', $row->id) . '" class="dropdown-item">' . __('View') . '</a>';
                }

                $html .= '<a href="' . $domain . '" target="_blank" class="dropdown-item">' . __('Open Application') . '</a>';

                if (auth()->user()->can('tenants_update_payment_status') && $row->has_due_amount == BooleanType::True->value) {

                    $html .= '<a href="' . route('saas.tenants.update.payment.status.index', $row->id) . '" class="dropdown-item" id="receiveDueAmount">' . __('Update Payment Status') . '</a>';
                }

                if (auth()->user()->can('tenants_upgrade_plan') && $row->is_trial_plan == BooleanType::True->value) {

                    $html .= '<a href="' . route('saas.tenants.upgrade.plan.cart', $row->id) . '" class="dropdown-item">' . __('Upgrade Plan') . '</a>';
                }

                if (auth()->user()->can('tenants_update_expire_date') && $row->is_trial_plan == BooleanType::False->value) {

                    $html .= '<a href="' . route('saas.tenants.update.expire.date.index', $row->id) . '" class="dropdown-item">' . __('Update Expire Date') . '</a>';
                }

                if (auth()->user()->can('tenants_destroy')) {

                    $html .= '<a href="' . route('saas.tenants.delete', $row->id) . '" class="dropdown-item">' . __('Delete') . '</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('created_at', function ($row) {

                return date('d-m-Y', strtotime($row->created_at));
            })
            ->editColumn('business_name', function ($row) {

                $data = json_decode($row->data, true);
                return $data['name'];
            })
            ->editColumn('domain', function ($row) {

                $domain = \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($row->domain);
                return '<a href="' . $domain . '" target="_blank" class="dropdown-item text-primary">' . $domain . '</a>';
            })

            ->editColumn('plan', function ($row) {

                if ($row->is_trial_plan) {

                    $startDate = new \DateTime($row->trial_start_date);
                    $endDate = clone $startDate;
                    // Add 7 days to today's date
                    $lastDate = $endDate->modify('+1 ' . $row->is_trial_plan . ' days');
                    // $lastDate = $lastDate->modify('+1 days');

                    $expireOn = $lastDate->format('d-m-Y');

                    return $row->plan_name . '(<span class="text-danger">Expire On: ' . $expireOn . '</span>)';
                } else {

                    return $row->plan_name;
                }
            })

            ->editColumn('has_business', function ($row) {

                if ($row->has_business == 1) {

                    return '<span class="text-success">Yes</span>';
                } else {

                    return '<span class="text-danger">No</span>';
                }
            })

            ->editColumn('payment_status', function ($row) {

                if ($row->has_due_amount == 1) {

                    $dueRepaymentDate = date('d-m-Y', strtotime($row->due_repayment_date));
                    return '<span class="text-danger">Due</span>' . '(<span class="text-danger">Repayment Date On: ' . $dueRepaymentDate . '</span>)';
                } else {

                    return '<span class="text-success">Paid</span>';
                }
            })

            ->rawColumns(['action', 'created_at', 'domain', 'plan', 'has_business', 'payment_status'])
            ->make(true);
    }

    public function deleteTenant(?string $id, bool $checkPassword = false, ?string $password = null): array
    {
        if ($checkPassword == true) {

            if (!Hash::check($password, auth()->user()->password)) {

                return ['pass' => false, 'msg' => 'Password does not match.'];
            }
        }

        Tenancy::find($id)?->delete();

        $this->removeDir(dir: public_path('uploads/' . $id));

        return ['pass' => true];
    }

    private function removeDir(string $dir): void
    {
        if (is_dir($dir)) {

            $files = scandir($dir);

            foreach ($files as $file) {

                if ($file != "." && $file != "..") {

                    $path = "$dir/$file";
                    if (is_dir($path)) {

                        $this->removeDir($path);
                    } else {

                        unlink($path);
                    }
                }
            }

            rmdir($dir);
        }
    }

    public function singleTenant(string $id, ?array $with = null): ?Tenant
    {
        $query = Tenant::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }
}
