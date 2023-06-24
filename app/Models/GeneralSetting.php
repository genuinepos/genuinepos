<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralSetting extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        \Log::info('GeneralSetting Called');
    }

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
}
