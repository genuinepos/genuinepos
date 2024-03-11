<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = ['domain', 'tenant_id'];

    public function scopeIsAvailable($query, $newDomain)
    {
        return ! $query->where('domain', $newDomain)->exists();
    }
}
