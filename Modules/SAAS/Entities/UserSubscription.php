<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(UserSubscriptionTransaction::class, 'user_subscription_id');
    }
}
