<?php

namespace App\Models\Essential;
use App\Models\BaseModel;

class Memo extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function memo_users()
    {
        return $this->hasMany(MemoUser::class);
    }
}
