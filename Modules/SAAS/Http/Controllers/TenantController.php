<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Tenant;
use Modules\SAAS\Http\Requests\TenantStoreRequest;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();

        return view('saas::tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('saas::tenants.create');
    }

    public function store(TenantStoreRequest $request)
    {
        $tenantRequest = $request->validated();
        $tenant = Tenant::create([
            'id' => $tenantRequest['domain'],
        ]);
        if ($tenant) {
            $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
            // return \redirect(route('saas.tenants.index'))->with('success', 'Tenant created successfully!');
            return view('saas::tenants.response', compact('tenant'));
        }
    }
}