<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'price', 'period_month', 'status'];

    protected static function newFactory()
    {
        return \Modules\SAAS\Database\factories\PlanFactory::new();
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features', 'plan_id', 'feature_id')
            ->withPivot('capacity');
    }

    public function getPeriodTypeAttribute()
    {
        return match ($this->period_month) {
            1 => __('Monthly'),
            3 => __('Quarterly'),
            6 => __('Half Yearly'),
            12 => __('Yearly'),
            default => __('Per') . ' ' . $this->period_month . ' ' .  __('Month'),
        };
    }
}
