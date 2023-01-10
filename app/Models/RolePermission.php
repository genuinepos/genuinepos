<?php

namespace App\Models;

use App\Models\BaseModel;

class RolePermission extends BaseModel
{
    protected $casts = [
        'user' => 'array',
        'contact' => 'array',
        'product' => 'array',
        'purchase' => 'array',
        's_adjust' => 'array',
        'expense' => 'array',
        'sale' => 'array',
        'register' => 'array',
        'report' => 'array',
        'setup' => 'array',
        'dashboard' => 'array',
        'accounting' => 'array',
        'hrms' => 'array',
        'essential' => 'array',
        'manufacturing' => 'array',
        'project' => 'array',
        'repair' => 'array',
        'superadmin' => 'array',
        'e_commerce' => 'array',
        'others' => 'array',
    ];
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
}
