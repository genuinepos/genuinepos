<?php

namespace App\Models\TaskManagement;

use App\Models\BaseModel;
use App\Models\TaskManagement\MemoUser;

class Memo extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function memo_users()
    {
        return $this->hasMany(MemoUser::class);
    }
}
