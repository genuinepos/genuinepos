<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\SAAS\Entities\UserSubscriptionTransaction;

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

    public function dueSubscriptionTransaction()
    {
        return $this->hasOne(UserSubscriptionTransaction::class)->where('due', '>', 0);
    }
}
