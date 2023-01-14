<?php

namespace App\Models;

use App\Models\BulkVariantChild;
use App\Models\BaseModel;

class BulkVariant extends BaseModel
{
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function bulk_variant_child()
    {
        return $this->hasMany(BulkVariantChild::class, 'bulk_variant_id');
    }

}
