<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopExpireDateHistory extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'count', 'created_count', 'left_count', 'start_at', 'end_at'
    // ];

    protected $guarded = [];

    // protected $casts = [
    //     'start_at' => 'datetime',
    //     'end_at' => 'datetime',
    // ];
}
