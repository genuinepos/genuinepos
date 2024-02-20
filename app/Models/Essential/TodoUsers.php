<?php

namespace App\Models\Essential;

use App\Models\BaseModel;
use App\Models\User;

class TodoUsers extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
