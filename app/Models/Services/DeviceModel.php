<?php

namespace App\Models\Services;

use App\Models\Services\JobCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceModel extends Model
{
    use HasFactory;

    protected $table = 'service_device_models';

    public function jobCards()
    {
        return $this->hasMany(JobCard::class, 'device_model_id');
    }
}
