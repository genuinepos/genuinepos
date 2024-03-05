<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\TaskManagement\Workspace;

class WorkspaceUsers extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'prefix', 'name', 'last_name');
    }
}
