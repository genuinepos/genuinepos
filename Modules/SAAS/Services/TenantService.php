<?php

namespace Modules\SAAS\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Payment;
use App\Models\GeneralSetting;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Models\Subscriptions\Subscription;
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

            // $expireAt = $plan->expireAt();

            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
                'impersonate_user' => 1,
                'plan_id' => $tenantRequest['plan_id'],
                // 'shop_count' => $tenantRequest['shop_count'],
                // 'expire_at' => null,
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
            'addons__branch_limit' => $plan->is_trial_plan == 1 ? $plan->trial_shop_count : $tenantRequest['shop_count'],
            'business_or_shop__address' => $tenantRequest['address'],
            // 'addons__cash_counter_limit' => $addons__cash_counter_limit,
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
            'emp_id' => '1001',
            'username' => explode('@', $tenantRequest['email'])[0],
            'email' => $tenantRequest['email'],
            'password' => bcrypt($tenantRequest['password']),
            'role_type' => 1,
            'allow_login' => 1,
            'status' => 1,
            'phone' => 'XXXXXXXXX',
            'date_of_birth' => '0000-00-00',
            'photo' => 'default.png',
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
        $subscribe->status = 1;
        $subscribe->initial_plan_start_date = Carbon::now();
        $subscribe->initial_shop_count = $plan->is_trial_plan == 1 ? $plan->trial_shop_count : $tenantRequest['shop_count'];
        $subscribe->current_shop_count = $plan->is_trial_plan == 1 ? $plan->trial_shop_count : $tenantRequest['shop_count'];

        if ($plan->is_trial_plan == 0) {

            $subscribe->initial_plan_start_date = Carbon::now();
            $subscribe->initial_price_period = $tenantRequest['price_period'] ? $tenantRequest['price_period'] : null;
            $subscribe->initial_period_count = $tenantRequest['period_count'] == 'lifetime' ? $plan->applicable_lifetime_years : $tenantRequest['period_count'];
            $subscribe->initial_plan_price = $tenantRequest['plan_price'] ? $tenantRequest['plan_price'] : 0;

            $subscribe->initial_subtotal = $tenantRequest['subtotal'] ? $tenantRequest['subtotal'] : 0;
            $subscribe->initial_discount = $tenantRequest['discount'] ? $tenantRequest['discount'] : 0;
            $subscribe->initial_total_payable_amount = $tenantRequest['total_payable'] ? $tenantRequest['total_payable'] : 0;

            if (isset($tenantRequest['has_business'])) {

                $subscribe->has_business = 1;
                $subscribe->initial_business_price_period = $tenantRequest['business_price_period'] ? $tenantRequest['business_price_period'] : 0;
                $subscribe->initial_business_price = $tenantRequest['business_price'] ? $tenantRequest['business_price'] : 0;
                $subscribe->initial_business_period_count = $tenantRequest['business_price_period'] == 'lifetime' ? $plan->applicable_lifetime_years : $tenantRequest['business_period_count'];
                $subscribe->initial_business_subtotal = $tenantRequest['business_subtotal'] ? $tenantRequest['business_subtotal'] : 0;
                $subscribe->initial_business_start_date = Carbon::now();

                $expireDate = $this->getExpireDate(period: $tenantRequest['business_price_period'], periodCount: $tenantRequest['business_price_period'] == 'lifetime' ? $plan->applicable_lifetime_years : $tenantRequest['business_period_count']);

                $subscribe->business_expire_date = $expireDate;
            }

            $subscribe->initial_payment_status = $tenantRequest['payment_status'];
            if ($tenantRequest['payment_status'] == 0) {

                $subscribe->initial_due_amount = $tenantRequest['total_payable'] ? $tenantRequest['total_payable'] : 0;
                $subscribe->initial_plan_expire_date = $tenantRequest['repayment_date'] ? date('Y-m-d', strtotime($tenantRequest['repayment_date'])) : null;
            } elseif ($tenantRequest['payment_status'] == 1) {

                $subscribe->initial_due_amount = 0;
            }
        } elseif ($plan->is_trial_plan == 1) {

            $subscribe->trial_start_date = Carbon::now();
            $subscribe->has_business = 1;
        }

        $subscribe->save();

        if ($plan->is_trial_plan == 0 && $subscribe->initial_payment_status == 1) {

            $this->storeSubscriptionTransaction($subscribe, $tenantRequest);
        }

        if ($plan->is_trial_plan == 0) {

            $this->storeShopExpireHistory($tenantRequest);
        }
    }

    protected function storeSubscriptionTransaction($subscribe, $tenantRequest)
    {
        $payment = new SubscriptionTransaction();
        $payment->transaction_type = 0;
        $payment->subscription_id = $subscribe->id;
        $payment->plan_id = $subscribe->plan_id;
        $payment->increase_shop_count = $subscribe->initial_shop_count;
        $payment->payment_method_name = $tenantRequest['payment_method_name'];
        $payment->payment_trans_id = $tenantRequest['payment_trans_id'];
        $payment->subtotal = $subscribe->initial_subtotal;
        $payment->discount = $subscribe->initial_discount;
        $payment->total_payable_amount = $subscribe->initial_total_payable_amount;
        $payment->paid = $subscribe->initial_total_payable_amount;
        $payment->payment_status = 1;
        $payment->payment_date = Carbon::now();
        $payment->save();
    }

    protected function storeShopExpireHistory($tenantRequest, $plan)
    {
        $expireDate = '';
        if ($tenantRequest['price_period'] == 'month') {

            $expireDate = $this->getExpireDate(period: 'month', periodCount: $tenantRequest['period_count']);
        } else if ($tenantRequest['price_period'] == 'year') {

            $expireDate = $this->getExpireDate(period: 'year', periodCount: $tenantRequest['period_count']);
        } else if ($tenantRequest['lifetime'] == 'lifetime') {

            $expireDate = $this->getExpireDate(period: 'year', periodCount: $plan->applicable_lifetime_years);
        }

        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->shop_count = $tenantRequest['shop_count'];
        $shopHistory->start_date = Carbon::now();
        $shopHistory->expire_date = $expireDate;
        $shopHistory->created_count = 0;
        $shopHistory->left_count = $tenantRequest['shop_count'];
        $shopHistory->save();
    }

    private function getExpireDate(string $period, int $periodCount)
    {
        $today = new \DateTime();

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
