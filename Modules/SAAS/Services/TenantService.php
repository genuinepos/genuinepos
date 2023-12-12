<?php

namespace Modules\SAAS\Services;

use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SAAS\Database\factories\AdminFactory;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Tenant;

class TenantService implements TenantServiceInterface
{
    public function create(array $tenantRequest): ?Tenant
    {
        try {
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
                    // Shared user
                    $user = User::create([
                        'name' => $tenantRequest['fullname'],
                        'email' => $tenantRequest['email'],
                        'password' => bcrypt($tenantRequest['password']),
                        'phone' => $tenantRequest['phone'],
                        'primary_tenant_id' => $tenant->id,
                        'ip_address' => request()->ip(),
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
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return null;
        }
    }

    public function saveBusinessSettings(array $tenantRequest) : void
    {
        $settings = [
            'business__business_name' => $tenantRequest['name'],
            'business__phone' => $tenantRequest['phone'],
            'business__email' => $tenantRequest['email'],

            'addons__branch_limit' => $tenantRequest['shop_count'],
            // 'business__address' => $business__address,
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
        $admin = (new AdminFactory)->definition();
        $admin['username'] = $tenantRequest['email'];
        $admin['email'] = $tenantRequest['email'];
        $admin['password'] = bcrypt($tenantRequest['password']);

        return $admin;
    }
}
