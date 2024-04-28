<?php

namespace Modules\SAAS\Http\Controllers;

use Exception;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Plan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Entities\Currency;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Services\TenantServiceInterface;
use Modules\SAAS\Http\Requests\TenantStoreRequest;
use Modules\SAAS\Http\Requests\TenantDeleteRequest;

class TenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
        private PlanServiceInterface $planServiceInterface,
    ) {
    }

    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('tenants_index'), 403);

        if ($request->ajax()) {

            return $this->tenantService->tenantsTable();
        }

        return view('saas::tenants.index');
    }

    public function show($id)
    {
        $tenant = $this->tenantService->singleTenant(id: $id, with: ['user', 'user.userSubscription', 'user.userSubscription.plan']);
        return view('saas::tenants.show', compact('tenant'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('tenants_create'), 403);
        $plans = $this->planServiceInterface->plans()->where('status', BooleanType::True->value)->get();
        $currencies = Currency::select('id', 'country', 'currency', 'code')->get();

        return view('saas::tenants.create', compact('plans', 'currencies'));
    }

    public function store(TenantStoreRequest $request)
    {
        $tenant = $this->tenantService->addTenant(request: $request);

        if (isset($tenant)) {

            // return route('saas.tenants.index');
            return response()->json(__('App created successfully'), 201);
        }

        $this->tenantService->deleteTenant(id: $request->domain);
        throw new Exception('Something went wrong, Business creation failed. Please try again!', 500);
    }

    public function delete($id)
    {
        abort_unless(auth()->user()->can('tenants_destroy'), 403);
        $tenant = $this->tenantService->singleTenant(id: $id, with: ['user']);
        return view('saas::tenants.delete_tenant', compact('tenant'));
    }

    public function destroy($id, TenantDeleteRequest $request)
    {
        $tenant = $this->tenantService->deleteTenant(id: $id, checkPassword: true, password: $request->password);

        if ($tenant['pass'] == false) {

            return response()->json(['errorMsg' => $tenant['msg']]);
        }

        return response()->json('Customer deleted successfully.');
    }
}
