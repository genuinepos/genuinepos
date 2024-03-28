<?php

namespace Modules\SAAS\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Payment;
use App\Enums\BooleanType;
use App\Models\GeneralSetting;
use Modules\SAAS\Entities\Plan;
use App\Mail\NewSubscriptionMail;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Models\Subscriptions\Subscription;
use App\Enums\SubscriptionTransactionDetailsType;
use Modules\SAAS\Database\factories\AdminFactory;
use App\Models\Subscriptions\ShopExpireDateHistory;
use App\Models\Subscriptions\SubscriptionTransaction;

class TenantService implements TenantServiceInterface
{
    public function create(array $tenantRequest): ?Tenant
    {
        try {
            DB::beginTransaction();
            $plan = Plan::find($tenantRequest['plan_id']);

            $expireDate = '';
            if ($plan->is_trial_plan == BooleanType::False->value) {

                if ($tenantRequest['shop_price_period'] == 'month') {

                    $expireDate = $this->getExpireDate(period: 'month', periodCount: $tenantRequest['shop_price_period_count']);
                } else if ($tenantRequest['shop_price_period'] == 'year') {

                    $expireDate = $this->getExpireDate(period: 'year', periodCount: $tenantRequest['shop_price_period_count']);
                } else if ($tenantRequest['shop_price_period'] == 'lifetime') {

                    $expireDate = $this->getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
                }
            } else if($plan->is_trial_plan == BooleanType::True->value) {

                $expireDate = $this->getExpireDate(period: 'day', periodCount: $plan->trial_days);
            }

            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
                'impersonate_user' => 1,
                'plan_id' => $tenantRequest['plan_id'],
                // 'shop_count' => $tenantRequest['shop_count'],
                'start_date' => Carbon::now(),
                'expire_date' => $expireDate ? $expireDate : null,
                'user_id' => 1,
            ]);

            if (isset($tenant)) {

                $domain = $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                if ($domain) {

                    // Primary/Owner user
                    // $user = User::create([
                    //     'name' => $tenantRequest['fullname'],
                    //     'email' => $tenantRequest['email'],
                    //     'password' => bcrypt($tenantRequest['password']),
                    //     'phone' => $tenantRequest['phone'],
                    //     'primary_tenant_id' => $tenant->id,
                    //     'ip_address' => request()->ip(),
                    // ]);

                    // $tenant->update([
                    //     'user_id' => $user->id,
                    // ]);

                    DB::statement('use ' . $tenant->tenancy_db_name);
                    $this->makeSuperAdminForTenant($tenantRequest);
                    // Insert settings coming from tenant creation form
                    $this->saveBusinessSettings($tenantRequest, $plan);

                    $this->storeSubscription($tenantRequest, $plan);

                    DB::reconnect();
                    Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);

                    dispatch(new \Modules\SAAS\Jobs\SendNewSubscriptionMailQueueJob(to: $tenantRequest['email'], user: $tenant));

                    return $tenant;
                }
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            Log::info($e->getMessage());
            DB::rollBack();
            return null;
        }
    }

    public function saveBusinessSettings(array $tenantRequest, object $plan): void
    {
        $settings = [
            'business_or_shop__business_name' => $tenantRequest['name'],
            'business_or_shop__phone' => $tenantRequest['phone'],
            'business_or_shop__email' => $tenantRequest['email'],
            'business_or_shop__address' => $tenantRequest['address'],
        ];

        foreach ($settings as $key => $setting) {

            GeneralSetting::where('key', $key)->update(['value' => $setting]);
        }
    }

    private function makeSuperAdminForTenant(array $tenantRequest): int
    {
        $admin = $this->getAdmin($tenantRequest);
        $tenantAdminUser = User::create($admin);
        $adminRole = Role::first();
        $tenantAdminUser->assignRole($adminRole);
        return $tenantAdminUser->id;
    }

    public function getAdmin(array $tenantRequest): array
    {
        // strtolower(str_replace(' ', '', str_replace('.', '', $tenantRequest['fullname'])));
        $admin = [
            'name' => $tenantRequest['fullname'],
            'username' => isset($tenantRequest['username']) ? $tenantRequest['username'] : explode('@', $tenantRequest['email'])[0],
            'email' => $tenantRequest['email'],
            'password' => bcrypt($tenantRequest['password']),
            'role_type' => 1,
            'allow_login' => 1,
            'status' => 1,
            'phone' => 'XXXXXXXXX',
            'date_of_birth' => '0000-00-00',
            'language' => 'en',
            'is_belonging_an_area' => 0,
            'currency_id' => $tenantRequest['currency_id'],
            'city' => $tenantRequest['city'],
            'postal_code' => $tenantRequest['postal_code'],
            'permanent_address' => $tenantRequest['address'],
            'current_address' => $tenantRequest['address'],
            'created_at' => Carbon::now(),
        ];

        // $admin = (new AdminFactory)->definition(request: $tenantRequest);
        // $admin['username'] = $tenantRequest['email'];
        // $admin['email'] = $tenantRequest['email'];
        // $admin['password'] = bcrypt($tenantRequest['password']);

        return $admin;
    }

    protected function storeSubscription($tenantRequest, $plan)
    {
        $subscribe = new Subscription();
        $subscribe->user_id = 1;
        $subscribe->plan_id = $plan->id;
        $subscribe->status = BooleanType::True->value;
        $subscribe->initial_plan_start_date = Carbon::now();
        $subscribe->current_shop_count = $plan->is_trial_plan == BooleanType::True->value ? $plan->trial_shop_count : $tenantRequest['shop_count'];

        if ($plan->is_trial_plan == BooleanType::False->value) {

            if (isset($tenantRequest['has_business'])) {

                $subscribe->has_business = BooleanType::True->value;
                $subscribe->business_start_date = Carbon::now();
                $subscribe->business_price_period = $tenantRequest['business_price_period'];
                $expireDate = $this->getExpireDate(period: $tenantRequest['business_price_period'] == 'lifetime' ? 'year' : $tenantRequest['business_price_period'], periodCount: $tenantRequest['business_price_period'] == 'lifetime' ? $plan->applicable_lifetime_years : $tenantRequest['business_price_period_count']);

                $subscribe->business_expire_date = $expireDate;

                $business = isset($tenantRequest['has_business']) ? 1 : 0;
                $shopPlusBusiness = $tenantRequest['shop_count'] + $business;
                $adjustablePrice = $tenantRequest['total_payable'] / $shopPlusBusiness;

                $subscribe->business_adjustable_price = $adjustablePrice;
            }

            $subscribe->has_due_amount = $tenantRequest['payment_status'] == BooleanType::False->value ? BooleanType::True->value : BooleanType::False->value;
            if ($tenantRequest['payment_status'] == BooleanType::False->value) {

                $subscribe->due_repayment_date = $tenantRequest['repayment_date'] ? date('Y-m-d', strtotime($tenantRequest['repayment_date'])) : null;
            }
        } elseif ($plan->is_trial_plan == BooleanType::True->value) {

            $subscribe->trial_start_date = Carbon::now();
            $subscribe->has_business = BooleanType::True->value;
        }

        $subscribe->save();

        if ($plan->is_trial_plan == BooleanType::False->value) {

            $this->storeSubscriptionTransaction($subscribe, $plan, $tenantRequest);
        }

        if ($plan->is_trial_plan == BooleanType::False->value) {

            $this->storeShopExpireHistory($tenantRequest, $plan);
        }
    }

    protected function storeSubscriptionTransaction($subscribe, $plan, $tenantRequest)
    {
        $payment = new SubscriptionTransaction();
        $payment->transaction_type = 0;
        $payment->subscription_id = $subscribe->id;
        $payment->plan_id = $subscribe->plan_id;
        $payment->payment_method_name = $tenantRequest['payment_method_name'];
        $payment->payment_trans_id = $tenantRequest['payment_trans_id'];
        $payment->net_total = $tenantRequest['net_total'];
        $payment->discount = isset($tenantRequest['discount']) ? $tenantRequest['discount'] : 0;
        $payment->total_payable_amount = $tenantRequest['total_payable'];
        $payment->paid = $tenantRequest['payment_status'] == BooleanType::True->value ? $tenantRequest['total_payable'] : 0;
        $payment->due = $tenantRequest['payment_status'] == BooleanType::False->value ? $tenantRequest['total_payable'] : 0;
        $payment->payment_status = $tenantRequest['payment_status'];
        $payment->payment_date = $tenantRequest['payment_status'] == BooleanType::True->value ? Carbon::now() : null;
        $payment->details_type = SubscriptionTransactionDetailsType::DirectBuyPlan->value;

        $subscriptionTransactionService = new \App\Services\Subscriptions\SubscriptionTransactionService();
        $request = (object) $tenantRequest;
        $transactionDetails = $subscriptionTransactionService->transactionDetails(request: $request, detailsType: SubscriptionTransactionDetailsType::DirectBuyPlan->value, plan: $plan);

        $payment->details = $transactionDetails;

        $payment->save();
    }

    protected function storeShopExpireHistory($tenantRequest, $plan)
    {
        $expireDate = '';
        if ($tenantRequest['shop_price_period'] == 'month') {

            $expireDate = $this->getExpireDate(period: 'month', periodCount: $tenantRequest['shop_price_period_count']);
        } else if ($tenantRequest['shop_price_period'] == 'year') {

            $expireDate = $this->getExpireDate(period: 'year', periodCount: $tenantRequest['shop_price_period_count']);
        } else if ($tenantRequest['shop_price_period'] == 'lifetime') {

            $expireDate = $this->getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
        }

        $business = isset($tenantRequest['has_business']) ? 1 : 0;
        $shopPlusBusiness = $tenantRequest['shop_count'] + $business;
        $adjustablePrice = $tenantRequest['total_payable'] / $shopPlusBusiness;

        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->shop_count = $tenantRequest['shop_count'];
        $shopHistory->price_period = $tenantRequest['shop_price_period'];
        $shopHistory->adjustable_price = $adjustablePrice;
        $shopHistory->start_date = Carbon::now();
        $shopHistory->expire_date = $expireDate;
        $shopHistory->created_count = 0;
        $shopHistory->left_count = $tenantRequest['shop_count'];
        $shopHistory->save();
    }

    private function getExpireDate(string $period, int $periodCount)
    {
        $today = new \DateTime();
        $lastDate = '';
        if ($period == 'day') {

            $lastDate = $today->modify('+' . $periodCount . ' days');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'month') {

            $lastDate = $today->modify('+' . $periodCount . ' months');
            $lastDate = $today->modify('+1 days');
        } elseif ($period == 'year') {

            $lastDate = $today->modify('+' . $periodCount . ' years');
            $lastDate = $today->modify('+1 days');
        }

        // Format the date
        return $lastDate->format('Y-m-d');
    }
}
