<?php

namespace Modules\SAAS\Entities;

use Modules\SAAS\Entities\Plan;
use Illuminate\Database\Eloquent\Model;
use Modules\SAAS\Entities\UserSubscription;

class UserSubscriptionTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    protected $casts = [
        'payment_date' => 'datetime',
        'details' => 'object',
    ];

    public function subscription()
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
