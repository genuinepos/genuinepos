<?php

namespace App\Services;

interface GeneralSettingServiceInterface
{
    public function updateAndSync(array $settings): bool;
    public function generalSettings(?int $branchId = null, array $keys = null): ?array;
    public function partiallyUpdateBusinessSettings(object $request): void;
    public function deleteBusinessLogo(): bool;
}
