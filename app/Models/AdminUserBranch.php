<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class AdminUserBranch extends BaseModel
{
    use HasFactory;
    protected $table = 'users_branches';
}
