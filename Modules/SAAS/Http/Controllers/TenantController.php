<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Tenant;
use Modules\SAAS\Http\Requests\TenantStoreRequest;
use Modules\SAAS\Services\TenantServiceInterface;

class TenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
    ) {
    }

    public function index()
    {
        abort_unless(auth()->user()->can('tenants_index'), 403);
        $tenants = Tenant::latest()->paginate();

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
        $tenant = $this->tenantService->create($tenantRequest);
        if (isset($tenant)) {
            return route('saas.tenants.index');
        }

        return response()->json('Something went wrong, please try again!', 500);
    }
}
