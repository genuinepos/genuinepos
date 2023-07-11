<?php

namespace App\Models;

class BulkVariantChild extends BaseModel
{
    public function bulk_variant()
    {
        return $this->belongsTo(BulkVariant::class, 'bulk_variant_id');
    }
}
