<?php

namespace Modules\SAAS\Entities;

use App\Models\User;
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isVerified()
    {
        $user = User::where('primary_tenant_id', $this->id)->first();
        return isset($user) && isset($user->email_verified_at);
    }

    public function haveExpired()
    {
        return isset($this->expire_at ) && today()->gt($this->expire_at);
    }
}
