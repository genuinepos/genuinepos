<?php

namespace Modules\SAAS\Http\Controllers;

use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Tenant;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Http\Requests\TenantStoreRequest;

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
        $plans = Plan::where('status', 1)->get();
        return view('saas::tenants.create', compact('plans'));
    }

    public function store(TenantStoreRequest $request)
    {
        abort_unless(auth()->user()->can('tenants_store'), 403);
        $tenantRequest = $request->validated();
        try {
            DB::beginTransaction();

            $tenant = $this->tenantService->create($tenantRequest);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if (isset($tenant)) {
            return route('saas.tenants.index');
        }

        return response()->json('Something went wrong, please try again!', 500);
    }
}
