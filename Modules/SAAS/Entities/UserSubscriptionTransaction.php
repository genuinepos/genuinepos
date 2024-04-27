<?php

namespace Modules\SAAS\Entities;

use Modules\SAAS\Entities\Plan;
use Illuminate\Database\Eloquent\Model;

class UserSubscriptionTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    protected $cast = ['details' => 'json'];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
