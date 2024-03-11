<?php

namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteUnusedTenantService
{
    function deleteTenant(string $domainName): void
    {
        DB::statement('use pos');
        $tenant = Tenant::where('id', $domainName)->first();
        if (isset($tenant)) {
            $tenant->delete();
        }
        DB::reconnect();
        $db = 'pos_' . $domainName;
        if (Schema::hasView($db)) {

            DB::statement('DROP DATABASE ' . $db);
        }
    }
}
