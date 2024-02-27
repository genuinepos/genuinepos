<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\SAAS\Entities\Plan;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Feature;
use Illuminate\Contracts\Support\Renderable;
use Modules\SAAS\Entities\Currency;

class PlanController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('plans_index'), 403);

        return view('saas::plans.index', [
            'plans' => Plan::with('currency')->paginate(),
        ]);
    }

    public function create()
    {
        abort_unless(auth()->user()->can('plans_create'), 403);

        $features = config('planfeatures');

        $currencies = Currency::whereIn('country', ['Bangladesh', 'United States of America'])
            ->select('id', 'code')
            ->get();

        return view('saas::plans.create', [
            'currencies' => $currencies,
            'features' => $features,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('plans_store'), 403);

        $checkFeatures = $request->features;
        unset($checkFeatures['user_count']);
        unset($checkFeatures['employee_count']);
        unset($checkFeatures['cash_counter_count']);
        unset($checkFeatures['warehouse_count']);

        if (count($checkFeatures) == 0) {

            return response()->json(['errorMsg' => 'Plan features are not selected.']);
        }

        $request->validate([
            'name' => 'required|unique:plans,name',
            'slug' => 'nullable|unique:plans,slug',
            'price_per_month' => 'required|numeric',
            'price_per_year' => 'required|numeric',
            'lifetime_price' => Rule::when($request->has_lifetime_period == 1, 'required|numeric'),
            'applicable_lifetime_years' => Rule::when($request->has_lifetime_period == 1, 'required|numeric'),
            'business_price_per_month' => 'required|numeric',
            'business_price_per_year' => 'required|numeric',
            'business_lifetime_price' => Rule::when($request->has_lifetime_period == 1, 'required|numeric'),
        ]);

        $preparedPlanFeatures = $this->preparedPlanFeatures($request);

        $plan = Plan::create([
            'name' => $request->name,
            'slug' => $request->slug ? $request->slug : Str::slug($request->name),
            'price_per_month' => $request->price_per_month,
            'price_per_year' => $request->price_per_year,
            'has_lifetime_period' => $request->has_lifetime_period,
            'lifetime_price' => $request->has_lifetime_period == 1 ? $request->lifetime_price : 0,
            'applicable_lifetime_years' => $request->has_lifetime_period == 1 ? $request->applicable_lifetime_years : 0,
            'business_price_per_month' => $request->business_price_per_month,
            'business_price_per_year' => $request->business_price_per_year,
            'business_lifetime_price' => $request->has_lifetime_period == 1 ? $request->business_lifetime_price : 0,
            'currency_id' => $request->currency_id,
            'description' => $request->description,
            'features' => $preparedPlanFeatures,
            'status' => $request->status,
        ]);

        return response()->json('Plan created successfully!');
    }

    public function show($id)
    {
        abort_unless(auth()->user()->can('plans_show'), 403);

        return view('saas::plans.show');
    }

    public function singlePlanById($id)
    {
        return Plan::with(['currency'])->where('id', $id)->first();
    }

    public function edit($id)
    {
        $currencies = Currency::whereIn('country', ['Bangladesh', 'United States of America'])
            ->select('id', 'code')->get();

        $features = config('planfeatures');

        return view('saas::plans.edit', [
            'currencies' => $currencies,
            'plan' => Plan::find($id),
            'features' => $features
        ]);
    }

    public function update(Request $request, $id)
    {
        $countExcept = ['user_count', 'employee_count', 'cash_counter_count', 'warehouse_count'];
        $checkFeatures = $request->features;
        unset($checkFeatures['user_count']);
        unset($checkFeatures['employee_count']);
        unset($checkFeatures['cash_counter_count']);
        unset($checkFeatures['warehouse_count']);

        if (count($checkFeatures) == 0) {

            return response()->json(['errorMsg' => 'Plan features are not selected.']);
        }

        $request->validate([
            'name' => 'required|unique:plans,name,' . $id,
            'price_per_month' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'price_per_year' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'lifetime_price' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
            'applicable_lifetime_years' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
            'currency_id' => Rule::when($request->is_trial_plan == 0, 'required'),
            'trial_days' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'trial_shop_count' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'status' => 'required',
            'business_price_per_month' => 'required',
            'business_price_per_year' => 'required',
            'business_lifetime_price' => Rule::when($request->is_trial_plan == 0 && $request->has_lifetime_period == 1, 'required|numeric'),
        ]);

        $updatePlan = Plan::where('id', $id)->first();
        $updatePlan->name = $request->name;
        $updatePlan->slug = $request->slug ? $request->slug : Str::slug($request->name);

        if ($updatePlan->is_trial_plan == 0) {
            $updatePlan->price_per_month = $request->price_per_month;
            $updatePlan->has_lifetime_period = $request->has_lifetime_period;
            $updatePlan->price_per_year = $request->price_per_year;
            $updatePlan->lifetime_price = $request->has_lifetime_period == 1 ? $request->lifetime_price : 0;
            $updatePlan->applicable_lifetime_years = $request->has_lifetime_period == 1 ? $request->applicable_lifetime_years : 0;
            $updatePlan->business_price_per_month = $request->business_price_per_month;
            $updatePlan->business_price_per_year = $request->business_price_per_year;
            $updatePlan->business_lifetime_price = $request->has_lifetime_period == 1 ? $request->business_lifetime_price : 0;
            $updatePlan->currency_id = $request->currency_id;
        } elseif ($updatePlan->is_trial_plan == 1) {
            $updatePlan->trial_days = $request->trial_days;
            $updatePlan->trial_shop_count = $request->trial_shop_count;
        }

        $updatePlan->features = $this->preparedPlanFeatures($request);
        $updatePlan->description = $request->description;
        $updatePlan->status = $request->status;
        $updatePlan->save();

        return response()->json('Plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);
        $plan->delete();

        return response()->json(__('Plan deleted successfully!'), 201);
    }

    private function preparedPlanFeatures($request)
    {
        return [
            'contacts' => isset($request->features['contacts']) ? 1 : 0,
            'inventory' => isset($request->features['inventory']) ? 1 : 0,
            'purchase' => isset($request->features['purchase']) ? 1 : 0,
            'sales' => isset($request->features['sales']) ? 1 : 0,
            'transfer_stocks' => isset($request->features['transfer_stocks']) ? 1 : 0,
            'stock_adjustments' => isset($request->features['stock_adjustments']) ? 1 : 0,
            'accounting' => isset($request->features['accounting']) ? 1 : 0,
            'users' => isset($request->features['users']) ? 1 : 0,
            'user_count' => isset($request->features['user_count']) ? $request->features['user_count'] : 0,
            'hrm' => isset($request->features['hrm']) ? 1 : 0,
            'employee_count' => isset($request->features['employee_count']) ? $request->features['employee_count'] : 0,
            'manufacturing' => isset($request->features['manufacturing']) ? $request->features['manufacturing'] : 0,
            'task_management' => isset($request->features['task_management']) ? 1 : 0,
            'communication' => isset($request->features['communication']) ? 1 : 0,
            'setup' => isset($request->features['setup']) ? 1 : 0,
            'cash_counter_count' => isset($request->features['cash_counter_count']) ? $request->features['cash_counter_count'] : 0,
            'warehouse_count' => isset($request->features['warehouse_count']) ? $request->features['warehouse_count'] : 0,
            'ecommerce' => isset($request->features['ecommerce']) ? 1 : 0,
        ];
    }
}
