<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Routing\Controller;
use Modules\SAAS\Utils\UrlGenerator;

use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Http\Requests\GuestTenantStoreRequest;

class GuestTenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
    ) {
    }

    public function store(GuestTenantStoreRequest $request)
    {
        $tenantRequest = $request->validated();
        $tenant = $this->tenantService->create($tenantRequest);
        if(isset($tenant)) {
            $domain = $tenant->domains()->where('domain', $tenantRequest['domain'])->first();
            $returningUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);
            return response()->json($returningUrl, 201);
        }
        return response()->json('Something went wrong, please try again!', 500);
    }
}
