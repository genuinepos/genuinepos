<?php

namespace Modules\SAAS\Entities;

use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Modules\SAAS\Entities\UserSubscriptionTransaction;

class UserSubscription extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function transactions()
    {
        return $this->hasMany(UserSubscriptionTransaction::class, 'user_subscription_id');
    }

    public function dueSubscriptionTransaction()
    {
        return $this->hasOne(UserSubscriptionTransaction::class)->where('due', '>', 0);
    }
}
