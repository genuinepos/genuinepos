<?php

namespace App\Models\Services;

use App\Models\Products\Brand;
use App\Models\Services\Device;
use App\Models\Services\Status;
use App\Models\Accounts\Account;
use App\Models\Services\DeviceModel;
use App\Models\Services\JobCardProduct;
use App\Models\Setups\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCard extends Model
{
    use HasFactory;

    protected $table = 'service_job_cards';

    protected $casts = [
        'service_checklist' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_account_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function deviceModel()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function jobCardProducts()
    {
        return $this->hasMany(JobCardProduct::class, 'job_card_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
