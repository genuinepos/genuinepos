<?php

namespace App\Models\Products;

use App\Models\BaseModel;
use App\Models\User;

class BulkVariant extends BaseModel
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function bulkVariantChild()
    {
        return $this->hasMany(BulkVariantChild::class, 'bulk_variant_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
