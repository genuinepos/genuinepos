<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\SAAS\Entities\Tenant as EntitiesTenant;

class Tenant extends EntitiesTenant
{
    use HasFactory;
}