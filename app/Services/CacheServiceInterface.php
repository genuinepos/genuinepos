<?php

namespace App\Services;

interface CacheServiceInterface
{
    public function forgetGeneralSettingsCache(?int $branchId = null): void;
}
