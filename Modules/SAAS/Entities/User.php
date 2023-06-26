<?php

namespace Modules\SAAS\Entities;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SAAS\Entities\Tenant;

class User extends ModelsUser
{
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
