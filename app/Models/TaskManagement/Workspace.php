<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\TaskManagement\WorkspaceUsers;
use App\Models\TaskManagement\WorkspaceAttachment;

class Workspace extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['updated_at'];

    public function users()
    {
        return $this->hasMany(WorkspaceUsers::class);
    }

    public function attachments()
    {
        return $this->hasMany(WorkspaceAttachment::class, 'workspace_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
