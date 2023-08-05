<?php

namespace App\Services;

interface GeneralSettingServiceInterface
{
    public function updateAndSync(array $settings): bool;
}
