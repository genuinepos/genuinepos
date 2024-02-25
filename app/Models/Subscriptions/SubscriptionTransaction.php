<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Entities\Plan;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'datetime'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function plan()
    {
        DB::statement('use ' . env('DB_DATABASE'));
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
