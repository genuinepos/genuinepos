<?php 

namespace App\Services;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;

class CacheService implements CacheServiceInterface
{
    public function syncGeneralSettings()
    {
        $generalSettings = Cache::get('generalSettings');
        if(isset($generalSettings) && !empty($generalSettings)) {
            Cache::forget('generalSettings');
        }
        if(!isset($generalSettings) || empty($generalSettings)) {
            Cache::rememberForever('generalSettings', function () {
               return GeneralSetting::first()->toArray();
            });
        }
    }
    
    public function rememberGeneralSettings()
    {

    }
    public function removeGeneralSettings()
    {

    }
}