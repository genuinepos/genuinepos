<?php

namespace Modules\SAAS\Http\Controllers;

use Exception;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\SAAS\Http\Requests\TenantStoreRequest;

class TenantController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('tenants_index'), 403);
        $tenants = Tenant::all();

        return view('saas::tenants.index', compact('tenants'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('tenants_create'), 403);
        return view('saas::tenants.create');
    }

    public function store(TenantStoreRequest $request)
    {
        abort_unless(auth()->user()->can('tenants_store'), 403);
        $tenantRequest = $request->validated();
        try {
            $tenant = Tenant::create([
                'id' => $tenantRequest['domain'],
                'name' => $tenantRequest['name'],
            ]);

            if ($tenant) {
                $tenant->domains()->create(['domain' => $tenantRequest['domain']]);
                // return \redirect(route('saas.tenants.index'))->with('success', 'Tenant created successfully!');
                return view('saas::tenants.response', compact('tenant'));
            }
        } catch (Exception $e) {
            // Log::error($e->getMessage());
            if (config('app.debug')) {
                return redirect()->back()->with('error', 'Tenant creation failed. ' . $e->getMessage());
            }
            return redirect()->back()->with('error', 'Tenant creation failed. Try again!');
        }
    }
}
