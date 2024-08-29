<?php

namespace App\Models\Subscriptions;

use App\Models\Branches\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopExpireDateHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'shop_expire_date_history_id');
    }
}
