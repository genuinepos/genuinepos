<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'plan_id', 'payment_id', 'start_time', 'end_time'];
}
