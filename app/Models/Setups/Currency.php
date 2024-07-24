<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use App\Models\GeneralSetting;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    protected $table = 'currencies';
    protected $hidden = ['created_at', 'updated_at'];
    use HasFactory;

    public function isBaseCurrency()
    {
        return $this->hasOne(GeneralSetting::class, 'value')->where('key', 'business_or_shop__currency_id')->where('branch_id', null);
    }

    public function assignedBranches()
    {
        return $this->hasMany(GeneralSetting::class, 'value')->where('key', 'business_or_shop__currency_id');
    }
}
