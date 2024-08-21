<?php

namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\Tenant;

interface TenantServiceInterface
{
    public function tenantsTable(): object;
    public function addTenant(object $request): ?Tenant;
    public function singleTenant(string $id, ?array $with = null): ?Tenant;
    public function deleteTenant(?string $id, bool $checkPassword = false, ?string $password = null): array;
}
