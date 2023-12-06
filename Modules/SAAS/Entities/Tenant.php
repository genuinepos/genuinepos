<?php

namespace Modules\SAAS\Entities;

use Modules\SAAS\Entities\Plan;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\MaintenanceMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory, MaintenanceMode;

    public $guarded = [];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
