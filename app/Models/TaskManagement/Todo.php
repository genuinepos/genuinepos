<?php

namespace App\Models\TaskManagement;

use App\Models\User;
use App\Models\BaseModel;
use App\Models\TaskManagement\TodoUsers;

class Todo extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(TodoUsers::class);
    }
}
