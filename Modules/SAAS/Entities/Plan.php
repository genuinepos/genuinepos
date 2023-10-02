<?php

namespace Modules\SAAS\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SAAS\Scope\IsActive;

class Plan extends Model
{
    use HasFactory;
    use IsActive;

    protected $fillable = ['name', 'slug', 'description', 'price', 'period_unit', 'period_value', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

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
        $periodType = '';
        if($this->period_unit === 'month') {
            $periodType = match ($this->period_value) {
                1 => __('Monthly'),
                3 => __('Quarterly'),
                6 => __('Half Yearly'),
                12 => __('Yearly'),
                default => __('Per') . ' ' . $this->period_value . ' ' .  __('Month'),
            };
        }
        if($this->period_unit === 'year') {
            $periodType = match ($this->period_value) {
                1 => __('Yearly'),
                default => __('Per') . ' ' . $this->period_value . ' ' .  __('Year'),
            };
        }
        if($this->period_unit === 'day') {
            $periodType = match ($this->period_value) {
                default => __('Per') . ' ' . $this->period_value . ' ' .  __('Day'),
            };
        }
        return $periodType;
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">In-Active</span>';
    }
}
