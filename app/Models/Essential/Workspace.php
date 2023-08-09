<?php

namespace App\Models\Essential;

use App\Models\BaseModel;
use App\Models\User;

class Workspace extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function ws_users()
    {
        return $this->hasMany(WorkspaceUsers::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->select('id', 'prefix', 'name', 'last_name');
    }
}
