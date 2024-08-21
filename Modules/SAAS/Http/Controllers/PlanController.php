<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SAAS\Http\Requests\PlanEditRequest;
use Modules\SAAS\Http\Requests\PlanIndexRequest;
use Modules\SAAS\Http\Requests\PlanStoreRequest;
use Modules\SAAS\Http\Requests\PlanCreateRequest;
use Modules\SAAS\Http\Requests\PlanDeleteRequest;
use Modules\SAAS\Http\Requests\PlanUpdateRequest;
use Modules\SAAS\Interfaces\PlanServiceInterface;
use Modules\SAAS\Interfaces\CurrencyServiceInterface;

class PlanController extends Controller
{
    public function __construct(
        private PlanServiceInterface $planServiceInterface,
        private CurrencyServiceInterface $currencyServiceInterface,
    ) {
    }

    public function index(PlanIndexRequest $request)
    {
        $plans = $this->planServiceInterface->plans()->paginate();

        return view('saas::plans.index', compact('plans'));
    }

    public function create(PlanCreateRequest $request)
    {
        $features = config('planfeatures');

        return view('saas::plans.create', compact('features'));
    }

    public function store(PlanStoreRequest $request)
    {
        $storePlan = $this->planServiceInterface->storePlan(request: $request);

        if (isset($storePlan['pass']) && $storePlan['pass'] == false) {

            return response()->json(['errorMsg' => $storePlan['msg']]);
        }

        return response()->json(__('Plan created successfully!'));
    }

    public function show($id)
    {
        return view('saas::plans.show');
    }

    public function singlePlanById($id)
    {
        return $this->planServiceInterface->singlePlanById(id: $id);
    }

    public function edit($id, PlanEditRequest $request)
    {
        $features = config('planfeatures');

        $plan = $this->planServiceInterface->singlePlanById(id: $id);

        return view('saas::plans.edit', compact('plan', 'features'));
    }

    public function update(PlanUpdateRequest $request, $id)
    {
        $updatePlan = $this->planServiceInterface->updatePlan(request: $request, id: $id);

        if (isset($updatePlan['pass']) && $updatePlan['pass'] == false) {

            return response()->json(['errorMsg' => $updatePlan['msg']]);
        }

        return response()->json(__('Plan updated successfully!'));
    }

    public function destroy($id, PlanDeleteRequest $request)
    {
        $deletePlan = $this->planServiceInterface->deletePlan(id: $id);

        if ($deletePlan['pass'] == false) {

            return response()->json(['errorMsg' => $deletePlan['msg']]);
        }

        return response()->json(__('Plan deleted successfully!'), 201);
    }
}
