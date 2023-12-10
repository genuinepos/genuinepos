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
        $this->period_unit = strtolower($this->period_unit);
        if ($this->period_unit === 'month') {
            $periodType = match ($this->period_value) {
                1 => __('Monthly'),
                12 => __('Yearly'),
                default => $this->period_value.' '.__('Months'),
            };
        }
        if ($this->period_unit === 'year') {
            $periodType = match ($this->period_value) {
                1 => __('Yearly'),
                default => $this->period_value.' '.__('Years'),
            };
        }
        if ($this->period_unit === 'day') {
            $periodType = match ($this->period_value) {
                default => $this->period_value.' '.__('Days'),
            };
        }

        return $periodType;
    }

    public function expireAt()
    {
        $expireAt = now();
        $this->period_unit = strtolower($this->period_unit);
        if ($this->period_unit === 'month' && isset($this->period_value)) {
           $expireAt = $expireAt->addMonths($this->period_value);
        }
        if ($this->period_unit === 'year' && isset($this->period_value)) {
            $expireAt = $expireAt->addYears($this->period_value);
        }
        if ($this->period_unit === 'day' && isset($this->period_value)) {
            $expireAt = $expireAt->addDays($this->period_value);
        }
        return $expireAt->format('Y-m-d H:i:s');
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">In-Active</span>';
    }
}
