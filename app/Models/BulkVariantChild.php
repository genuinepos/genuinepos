<?php

namespace App\Models;

use App\Models\BulkVariant;
use App\Models\BaseModel;

class BulkVariantChild extends BaseModel
{
    public function bulk_variant()
    {
        return $this->belongsTo(BulkVariant::class, 'bulk_variant_id');
    }
}
