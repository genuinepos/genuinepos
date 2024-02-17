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
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        abort_unless(auth()->user()->can('plans_index'), 403);

        return view('saas::plans.index', [
            'plans' => Plan::with('currency')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        abort_unless(auth()->user()->can('plans_create'), 403);

        $currencies = Currency::whereIn('country', ['Bangladesh', 'United States of America'])
        ->select('id','code')
        ->get();
        return view('saas::plans.create', [
            'currencies' => $currencies,
            'features' => Feature::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('plans_store'), 403);

        $request->validate([
            'name' => 'required|unique:plans,name',
            'price_per_month' => 'required|numeric',
            'price_per_year' => 'required|numeric',
            'lifetime_price' => 'required|numeric',
            'applicable_lifetime_years' => 'required|numeric'
        ]);

        $plan = Plan::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->slug),
            'price_per_month' => $request->price_per_month,
            'price_per_year' => $request->price_per_year,
            'lifetime_price' => $request->lifetime_price,
            'applicable_lifetime_years' => $request->applicable_lifetime_years,
            'currency_id' => $request->currency_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $plan->features()->sync($request->feature_id);

        return response()->json('Plan created successfully!');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        abort_unless(auth()->user()->can('plans_show'), 403);

        return view('saas::plans.show');
    }

    public function singlePlanById($id)
    {
        return Plan::with(['currency'])->where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $currencies = Currency::whereIn('country', ['Bangladesh', 'United States of America'])
        ->select('id','code')->get();

        return view('saas::plans.edit', [
            'currencies' => $currencies,
            'plan' => Plan::find($id),
            'features' => Feature::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:plans,name,' . $id,
            'price_per_month' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'price_per_year' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'lifetime_price' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'applicable_lifetime_years' => Rule::when($request->is_trial_plan == 0, 'required|numeric'),
            'currency_id' => Rule::when($request->is_trial_plan == 0, 'required'),
            'trial_days' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'trial_shop_count' => Rule::when($request->is_trial_plan == 1, 'required|numeric'),
            'status' => 'required',
        ]);

        $updatePlan = Plan::where('id', $id)->first();
        $updatePlan->name = $request->name;
        $updatePlan->slug = $request->slug ?? Str::slug($request->slug);

        if ($updatePlan->is_trial_plan == 0) {

            $updatePlan->price_per_month = $request->price_per_month;
            $updatePlan->price_per_year = $request->price_per_year;
            $updatePlan->lifetime_price = $request->lifetime_price;
            $updatePlan->applicable_lifetime_years = $request->applicable_lifetime_years;
            $updatePlan->currency_id = $request->currency_id;
        }elseif($updatePlan->is_trial_plan == 1){

            $updatePlan->trial_days = $request->trial_days;
            $updatePlan->trial_shop_count = $request->trial_shop_count;
        }

        $updatePlan->description = $request->description;
        $updatePlan->status = $request->status;
        $updatePlan->save();

        $updatePlan->features()->sync($request->feature_id);

        return response()->json('Plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);
        $plan->delete();

        return response()->json(__('Plan deleted successfully!'), 201);
    }
}
