<?php

namespace App\Models\Services;

use App\Models\Services\JobCard;
use App\Models\Services\DeviceModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $table = 'service_devices';

    public function deviceModels()
    {
        return $this->hasMany(DeviceModel::class, 'device_id');
    }

    public function jobCards()
    {
        return $this->hasMany(JobCard::class, 'device_id');
    }
}
