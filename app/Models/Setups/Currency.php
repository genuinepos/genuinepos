<?php

namespace App\Models\Setups;

use App\Models\BaseModel;
use App\Models\GeneralSetting;
use App\Models\Setups\CurrencyRate;
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

    public function currencyRates()
    {
        return $this->hasMany(CurrencyRate::class, 'currency_id');
    }

    public function currentCurrencyRate()
    {
        return $this->hasOne(CurrencyRate::class, 'currency_id')->orderByDesc('date_ts');
    }
}
