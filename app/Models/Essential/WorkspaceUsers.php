<?php

namespace App\Models\Essential;
use App\Models\Essential\Workspace;
use Illuminate\Database\Eloquent\Model;

class WorkspaceUsers extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
}
