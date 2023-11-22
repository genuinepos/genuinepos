<?php

namespace Modules\SAAS\Services;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SAAS\Database\factories\AdminFactory;
use Modules\SAAS\Entities\Tenant;

class TenantService implements TenantServiceInterface
{
    public function create(array $tenantRequest): ?Tenant
    {
        try {
            $tenant = Tenant::create(['id' => $tenantRequest['domain'], 'name' => $tenantRequest['name']]);
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
                    $tenantAdminUserId = $this->makeSuperAdminForTenant($tenant, $tenantRequest);
                    $tenant->update(['impersonate_user' => $tenantAdminUserId]);

                    return $tenant;
                }
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());

            return null;
        }
    }

    private function makeSuperAdminForTenant(Tenant $tenant, array $tenantRequest): int
    {
        $admin = $this->getAdmin($tenantRequest);
        DB::statement('use '.$tenant->tenancy_db_name);
        $tenantAdminUser = User::create($admin);
        $adminRole = Role::first();
        $tenantAdminUser->assignRole($adminRole);
        DB::reconnect();

        return $tenantAdminUser->id;
    }

    public function getAdmin(array $tenantRequest): array
    {
        $admin = (new AdminFactory)->definition();
        $admin['username'] = $tenantRequest['email']; // TODO:: resolve username and email for same field now, change later
        $admin['email'] = $tenantRequest['email'];
        $admin['password'] = bcrypt($tenantRequest['password']);

        return $admin;
    }
}
