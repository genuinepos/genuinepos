<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{

    // use SoftDeletes;

    protected $table = 'coupons';

    protected $guarded = [];
}
