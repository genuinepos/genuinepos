<?php

namespace Modules\SAAS\Entities;

use App\Models\User as ModelsUser;
use Spatie\Permission\Traits\HasRoles;

class User extends ModelsUser
{
    use HasRoles;

    protected $guard_name = 'web';

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
