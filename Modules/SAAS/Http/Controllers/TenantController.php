<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Modules\SAAS\Http\Requests\TenantStoreRequest;

class TenantController extends Controller
{
    public function create()
    {
        return view('saas::tenants.create');
    }

    public function store(TenantStoreRequest $request)
    {
        $tenantRequest = $request->validated();
        $tenantRequest['id'] = Str::uuid()->toString();
        $tenant = Tenant::create([
            'id' => $tenantRequest['domain'],
        ]);
        if ($tenant) {
            $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
            return "Success";
        } else {
            dd('failed');
        }

    }
}
