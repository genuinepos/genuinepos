<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SAAS\Http\Requests\PlanStoreRequest;
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

    public function index()
    {
        abort_unless(auth()->user()->can('plans_index'), 403);

        $plans = $this->planServiceInterface->plans(with: ['currency'])->paginate();

        return view('saas::plans.index', compact('plans'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('plans_create'), 403);

        $features = config('planfeatures');

        $currencies = $this->currencyServiceInterface->currencies()
            ->whereIn('country', ['Bangladesh', 'United States of America'])
            ->select('id', 'code')
            ->get();

        return view('saas::plans.create', compact('currencies', 'features'));
    }

    public function store(PlanStoreRequest $request)
    {
        $storePlan = $this->planServiceInterface->storePlan(request: $request);

        if (isset($storePlan['pass']) && $storePlan['pass'] == false) {

            return response()->json(['errorMsg' => $storePlan['msg']]);
        }

        return response()->json('Plan created successfully!');
    }

    public function show($id)
    {
        abort_unless(auth()->user()->can('plans_show'), 403);

        return view('saas::plans.show');
    }

    public function singlePlanById($id)
    {
        return $this->planServiceInterface->singlePlanById(id: $id, with: ['currency']);
    }

    public function edit($id)
    {
        abort_unless(auth()->user()->can('plans_update'), 403);

        $currencies = $this->currencyServiceInterface->currencies()
            ->whereIn('country', ['Bangladesh', 'United States of America'])
            ->select('id', 'code')->get();

        $features = config('planfeatures');

        $plan = $this->planServiceInterface->singlePlanById(id: $id);

        return view('saas::plans.edit', compact('currencies', 'plan', 'features'));
    }

    public function update(PlanUpdateRequest $request, $id)
    {
        $updatePlan = $this->planServiceInterface->updatePlan(request: $request, id: $id);

        if (isset($updatePlan['pass']) && $updatePlan['pass'] == false) {

            return response()->json(['errorMsg' => $updatePlan['msg']]);
        }

        return response()->json('Plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = $this->planServiceInterface->singlePlanById(id: $id);
        $plan->delete();

        return response()->json(__('Plan deleted successfully!'), 201);
    }
}
