<?php

namespace App\Models\Services;

use App\Models\Products\Unit;
use App\Models\Products\Product;
use App\Models\Services\JobCard;
use App\Models\Products\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCardProduct extends Model
{
    use HasFactory;

    protected $table = 'service_job_card_products';

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class, 'job_card_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
