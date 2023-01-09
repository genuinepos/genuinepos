<?php 

namespace App\Services;
interface CacheServiceInterface
{
    public function syncGeneralSettings() : void;
    public function rememberGeneralSettings() : void;
    public function removeGeneralSettings() : void;
}