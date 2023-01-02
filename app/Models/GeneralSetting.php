<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

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
        return json_decode($query->first()->sms_setting, true);
    }

    public function scopeEmail($query)
    {
        $existing = $query->first()->email_setting;
        $existing =  isset($existing) && !empty($existing) ? $existing : '{}';
        return json_decode($existing, true);
    }

    public function scopeIsSmsActive($query)
    {
        return json_decode($query->first()->sms_setting, true)['status'] ? true : false;
    }

    public function scopeIsEmailActive($query)
    {
        return json_decode($query->first()->email_setting, true)['MAIL_ACTIVE'] ? true : false;
    }
}
