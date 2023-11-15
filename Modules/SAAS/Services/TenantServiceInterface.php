<?php

namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\Tenant;

interface TenantServiceInterface
{
    public function create(array $request): ?Tenant;
}
