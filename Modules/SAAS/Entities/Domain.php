<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;
    protected $fillable = ['domain', 'tenant_id'];
    public function scopeIsAvailable($query, $newDdomain)
    {
        return ! $query->where('domain', $newDdomain)->exists();
    }
}
