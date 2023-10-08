<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use App\Models\Products\BulkVariant;

class BulkVariantChild extends BaseModel
{
    protected $table = 'bulk_variant_children';
    
    public function bulkVariant()
    {
        return $this->belongsTo(BulkVariant::class, 'bulk_variant_id');
    }
}
