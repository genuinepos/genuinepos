<?php

namespace App\Models\Subscriptions;

use Modules\SAAS\Entities\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'plan_id', 'user_id', 'amount', 'shop_count', 'status', 'start_at', 'end_at', 'cancels_at', 'canceled_at'
    // ];

    protected $guarded = [];

    // protected $casts = [
    //     'start_at' => 'datetime',
    //     'expire_at' => 'datetime',
    // ];

    public function plan()
    {
        DB::statement('use pos');
        return $this->belongsTo(Plan::class, 'plan_id');
    }

}
