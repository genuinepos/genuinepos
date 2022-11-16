<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

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
