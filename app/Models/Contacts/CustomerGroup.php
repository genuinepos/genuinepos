<?php

namespace App\Models\Contacts;

use App\Models\BaseModel;
use App\Models\Products\PriceGroup;
use App\Models\Branches\Branch;

class CustomerGroup extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function priceGroup()
    {
        return $this->hasMany(PriceGroup::class, 'price_group_id');
    }

    public function branch()
    {
        return $this->hasMany(Branch::class, 'branch_id');
    }
}
