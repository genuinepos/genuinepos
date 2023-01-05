<?php 

namespace App\Services;
interface CacheServiceInterface
{
    public function syncGeneralSettings();
    public function rememberGeneralSettings();
    public function removeGeneralSettings();
}