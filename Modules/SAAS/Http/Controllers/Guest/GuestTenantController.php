<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Exception;
use Illuminate\Routing\Controller;
use Modules\SAAS\Http\Requests\GuestTenantStoreRequest;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Utils\UrlGenerator;

class GuestTenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
    ) {
    }

    public function store(GuestTenantStoreRequest $request)
    {
        $tenantRequest = $request->all();
        $tenant = $this->tenantService->create($tenantRequest);
        if (isset($tenant)) {
            $domain = $tenant->domains()->where('domain', $tenantRequest['domain'])->first();
            $returningUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);

            // return response()->json($returningUrl, 201);
            return redirect()->intended($returningUrl);
        }

        throw new Exception('Something went wrong, Business creation failed. Please try again!', 500);
    }
}
