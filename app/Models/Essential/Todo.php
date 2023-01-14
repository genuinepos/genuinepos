<?php

namespace App\Models\Essential;

use App\Models\User;
use App\Models\Essential\TodoUsers;
use App\Models\BaseModel;

class Todo extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }
    
    public function todo_users()
    {
        return $this->hasMany(TodoUsers::class);
    }
}
