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
use Modules\SAAS\Interfaces\CurrencyServiceInterface;

class TenantController extends Controller
{
    public function __construct(
        private TenantServiceInterface $tenantService,
        private CurrencyServiceInterface $tenantServiceInterface,
        private PlanServiceInterface $planServiceInterface,
    ) {}

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
        $tenant = $this->tenantService->singleTenant(id: $id, with: [
            'user',
            'user.userSubscription',
            'user.userSubscription.plan:id,name,is_trial_plan,trial_days'
        ]);

        DB::statement('use ' . $tenant->tenancy_db_name);
        $business = DB::table('general_settings')->where('key', 'business_or_shop__business_name')->select('value')->first();
        $branches = DB::table('branches')
            ->leftJoin('branches as parentBranch', 'branches.parent_branch_id', 'parentBranch.id')
            ->select(
                'branches.branch_type',
                'branches.category',
                'branches.area_name',
                'branches.name as branch_name',
                'branches.branch_code',
                'branches.expire_date',
                'parentBranch.name as parent_branch_name',
                'parentBranch.logo as parent_branch_logo',
                'parentBranch.category as parent_category',
            )->orderByRaw('COALESCE(branches.parent_branch_id, branches.id), branches.id')->get();
        DB::reconnect();

        return view('saas::tenants.show', compact('tenant', 'branches', 'business'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('tenants_create'), 403);
        $plans = $this->planServiceInterface->plans()->where('status', BooleanType::True->value)->get();
        $currencies = $this->tenantServiceInterface->currencies()->get(['id', 'country']);
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
        throw new Exception('Something went wrong, Company creation failed. Please try again!', 500);
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
