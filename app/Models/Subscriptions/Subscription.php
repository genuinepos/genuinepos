<?php

namespace App\Models\Subscriptions;

use App\Models\User;
use Modules\SAAS\Entities\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriptions\SubscriptionTransaction;
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
        DB::statement('use ' . env('DB_DATABASE'));
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dueSubscriptionTransaction()
    {
        return $this->hasOne(SubscriptionTransaction::class)->where('due', '>', 0);
    }
}
