<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_features', 'feature_id', 'plan_id');
    }
}
