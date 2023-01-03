<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        \Log::info("GeneralSetting Called");
    }

    protected $casts = [
        'business' => 'array',
        'tax' => 'array',
        'product' => 'array',
        'sale' => 'array',
        'pos' => 'array',
        'purchase' => 'array',
        'system' => 'array',
        'prefix' => 'array',
        'send_es_settings' => 'array',
        'email_setting' => 'array',
        'sms_setting' => 'array',
        'modules' => 'array',
        'reward_point_settings' => 'array',
        'mf_settings' => 'array',
        'multi_branches' => 'array',
        'hrm' => 'array',
        'services' => 'array',
        'manufacturing' => 'array',
        'projects' => 'array',
        'essentials' => 'array',
        'e_commerce' => 'array',
        'dashboard' => 'array',
    ];

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
