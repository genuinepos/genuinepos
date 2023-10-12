<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Exception;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Routing\Controller;
use Modules\SAAS\Utils\UrlGenerator;
use Modules\SAAS\Http\Requests\TenantStoreRequest;

class GuestTenantController extends Controller
{
    public function store(TenantStoreRequest $request)
    {
        $tenantRequest = $request->validated();
        // return 'http://apex.pos.test';

        try {
            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
            ]);

            if ($tenant) {
                $domain = $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                $returningUrl = UrlGenerator::generateFullUrlFromDomain($domain->domain);
                return response()->json($returningUrl, 200);
            }
        } catch (Exception $e) {
            if (config('app.debug')) {
                return redirect()->back()->with('error', 'Tenant creation failed.' . $e->getMessage());
            }
            return redirect()->back()->with('error', 'Something went wrong! please try again!');
        }
    }
}
