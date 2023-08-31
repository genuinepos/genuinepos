<?php

namespace Modules\SAAS\Entities;

use App\Models\User as ModelsUser;

class User extends ModelsUser
{
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function role()
    {
        return $this->roles->first()->name;
    }
}
