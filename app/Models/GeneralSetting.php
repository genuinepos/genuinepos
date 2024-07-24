<?php

namespace App\Models;

use App\Models\Setups\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralSetting extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    public function scopeSms($query)
    {
        return $this->sms_setting ?? [];
    }

    public function scopeEmail($query)
    {
        return $this->email_setting ?? [];
    }

    public function scopeIsSmsActive($query)
    {
        return ($this->sms_setting['status'] ?? false) ? true : false;
    }

    public function scopeIsEmailActive($query)
    {
        return ($this->email_setting['MAIL_ACTIVE'] ?? false) ? true : false;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
