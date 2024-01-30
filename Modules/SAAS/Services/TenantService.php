<?php

namespace Modules\SAAS\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\GeneralSetting;
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
        // try {
            $plan = Plan::find($tenantRequest['plan_id']);
            $expireAt = $plan->expireAt();

            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
                'impersonate_user' => 1,
                'plan_id' => $tenantRequest['plan_id'],
                'shop_count' => $tenantRequest['shop_count'],
                'expire_at'=> $expireAt,
            ]);

            if (isset($tenant)) {

                $domain = $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                if ($domain) {

                    // Primary/Owner user
                    $user = User::create([
                        'name' => $tenantRequest['fullname'],
                        'email' => $tenantRequest['email'],
                        'password' => bcrypt($tenantRequest['password']),
                        'phone' => $tenantRequest['phone'],
                        'primary_tenant_id' => $tenant->id,
                        'ip_address' => request()->ip(),
                    ]);

                    $tenant->update([
                        'user_id' => $user->id,
                    ]);

                    DB::statement('use ' . $tenant->tenancy_db_name);
                    $this->makeSuperAdminForTenant($tenantRequest);
                    // Insert setings coming from tenant creation form
                    $this->saveBusinessSettings($tenantRequest);
                    DB::reconnect();
                    Artisan::call('tenants:run cache:clear --tenants=' . $tenant->id);
                    return $tenant;
                }
            }
        // } catch (Exception $e) {
        //     Log::debug($e->getMessage());
        //     return null;
        // }
    }

    public function saveBusinessSettings(array $tenantRequest) : void
    {
        $settings = [
            'business_or_shop__business_name' => $tenantRequest['name'],
            'business_or_shop__phone' => $tenantRequest['phone'],
            'business_or_shop__email' => $tenantRequest['email'],

            'addons__branch_limit' => $tenantRequest['shop_count'],
            // 'business_or_shop__address' => $business_or_shop__address,
            // 'addons__cash_counter_limit' => $addons__cash_counter_limit,
        ];

        foreach($settings as $key => $setting) {

            GeneralSetting::where('key', $key)->update(['value'=> $setting]);
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
        $admin = [
            'name' => $tenantRequest['fullname'],
            'emp_id' => '1001',
            'username' => strtolower(str_replace(' ', '', str_replace('.', '', $tenantRequest['fullname']))),
            'email' => $tenantRequest['email'],
            'password' => bcrypt($tenantRequest['password']),
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'status' => 1,
            'phone' => 'XXXXXXXXX',
            'date_of_birth' => '0000-00-00',
            'photo' => 'default.png',
            'language' => 'en',
            'is_belonging_an_area' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ];

        // $admin = (new AdminFactory)->definition(request: $tenantRequest);
        // $admin['username'] = $tenantRequest['email'];
        // $admin['email'] = $tenantRequest['email'];
        // $admin['password'] = bcrypt($tenantRequest['password']);

        return $admin;
    }
}
