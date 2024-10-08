<?php

namespace App\Models\Contacts;

use App\Models\BaseModel;
use App\Models\Branches\Branch;

class MoneyReceipt extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
