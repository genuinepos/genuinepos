<?php

namespace Modules\SAAS\Services;

use Modules\SAAS\Entities\Tenant;
use Modules\SAAS\Http\Requests\TenantCreateRequest;

interface TenantServiceInterface
{
    public function create(array $request) : ?Tenant;
}
