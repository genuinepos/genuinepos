<?php

namespace Modules\SAAS\Services;

use Exception;
use App\Models\Role;
use App\Models\User;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\SAAS\Database\factories\AdminFactory;

class TenantService implements TenantServiceInterface
{
    public function create(array $tenantRequest): ?Tenant
    {
        try {
            $tenant = Tenant::create(['id' => $tenantRequest['domain'], 'name' => $tenantRequest['name']]);
            if (isset($tenant)) {
                $domain = $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                if($domain) {
                    $this->makeSuperAdminForTenant($tenant, $tenantRequest);
                    return $tenant;
                }
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return null;
        }
    }

    private function makeSuperAdminForTenant(Tenant $tenant, array $tenantRequest): void
    {
        $admin = $this->getAdmin($tenantRequest);
        DB::statement('use '.$tenant->tenancy_db_name);
        $admin = User::create($admin);
        $adminRole = Role::first();
        $admin->assignRole($adminRole);
        DB::reconnect();
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
