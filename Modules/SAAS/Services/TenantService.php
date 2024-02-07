<?php

namespace Modules\SAAS\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\ShopExpireDateHistory;
use App\Models\Payment;
use App\Models\Subscription;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Database\factories\AdminFactory;

class TenantService implements TenantServiceInterface
{
    public function create(array $tenantRequest): ?Tenant
    {
        try {
            DB::beginTransaction();
            $plan = Plan::find($tenantRequest['plan_id']);
            $expireAt = $plan->expireAt();

            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
                'impersonate_user' => 1,
                'plan_id' => $tenantRequest['plan_id'],
                'shop_count' => $tenantRequest['shop_count'],
                'expire_at' => $expireAt,
                'user_id' => auth()?->user()?->id ? auth()?->user()?->id : 1,
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
                    $this->makeSuperAdminForTenant($tenantRequest, $expireAt);
                    // Insert settings coming from tenant creation form
                    $this->saveBusinessSettings($tenantRequest);
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

    public function saveBusinessSettings(array $tenantRequest): void
    {
        $settings = [
            'business_or_shop__business_name' => $tenantRequest['name'],
            'business_or_shop__phone' => $tenantRequest['phone'],
            'business_or_shop__email' => $tenantRequest['email'],
            'addons__branch_limit' => $tenantRequest['shop_count'],
            'business_or_shop__address' => $tenantRequest['address'],
            // 'addons__cash_counter_limit' => $addons__cash_counter_limit,
        ];

        foreach ($settings as $key => $setting) {

            GeneralSetting::where('key', $key)->update(['value' => $setting]);
        }
    }

    private function makeSuperAdminForTenant(array $tenantRequest, $expireAt): int
    {
        $admin = $this->getAdmin($tenantRequest);
        $tenantAdminUser = User::create($admin);
        $adminRole = Role::first();
        $tenantAdminUser->assignRole($adminRole);
        $this->storeSubscription($tenantAdminUser, $tenantRequest, $expireAt);
        $this->storeShopExpireHistory($tenantRequest, $expireAt);
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
            'updated_at' => null,
            'plan_id' => $tenantRequest['plan_id'],
        ];

        // $admin = (new AdminFactory)->definition(request: $tenantRequest);
        // $admin['username'] = $tenantRequest['email'];
        // $admin['email'] = $tenantRequest['email'];
        // $admin['password'] = bcrypt($tenantRequest['password']);

        return $admin;
    }

    protected function storeSubscription($tenantAdminUser, $tenantRequest, $expireAt)
    {
        $subscribe = new Subscription();
        $subscribe->user_id = $tenantAdminUser->id;
        $subscribe->plan_id = $tenantRequest['plan_id'];
        $subscribe->amount = $tenantRequest['amount'] ?? 0;
        $subscribe->shop_count = $tenantRequest['shop_count'];
        $subscribe->status = 0;
        $subscribe->start_at = now();
        $subscribe->end_at = $expireAt;
        $subscribe->save();
    }

    protected function storeShopExpireHistory($tenantRequest, $expireAt)
    {
        $shopHistory = new ShopExpireDateHistory();
        $shopHistory->count = $tenantRequest['shop_count'];
        $shopHistory->start_at = now();
        $shopHistory->end_at = $expireAt;
        $shopHistory->created_count = 0;
        $shopHistory->left_count = $tenantRequest['shop_count'];
        $shopHistory->save();
    }

    protected function storeSubscriptionPayment($request, $subscribe, $tenantRequest, $shop)
    {
        $payment = new Payment();
        $payment->subscription_id = $subscribe->id;
        $payment->plan_id = $tenantRequest['plan_id'];
        $payment->plan_id = $shop->shop_id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->transaction_id = $request->transaction_id;
        $payment->subtotal = $request->subtotal;
        $payment->discount = $request->discount;
        $payment->total = $request->total;
        $payment->status = $request->status;
        $payment->payment_type = $request->payment_type;
        $payment->payment_at = now();
    }
}
