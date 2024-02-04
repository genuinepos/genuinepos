<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id', 'plan_id', 'shop_id', 'payment_method_id', 'transaction_id', 'amount', 'status', 'payment_at', 'detail',
        'subtotal', 'discount', 'total'
    ];

    protected $casts = [
        'payment_at' => 'datetime',
    ];
}
