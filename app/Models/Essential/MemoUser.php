<?php

namespace App\Models\Essential;

use App\Models\BaseModel;

class MemoUser extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];
}
