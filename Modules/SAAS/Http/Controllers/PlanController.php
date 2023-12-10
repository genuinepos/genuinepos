<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\SAAS\Entities\Feature;
use Modules\SAAS\Entities\Plan;

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
            'plans' => Plan::paginate(),
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

        return view('saas::plans.create', [
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
        $plan = Plan::create([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->slug),
            'currency_code' => $request->currency_code,
            'price' => $request->price,
            'description' => $request->description,
            'period_unit' => $request->period_unit,
            'period_value' => $request->period_value,
            'status' => $request->status,
        ]);

        $plan->features()->sync($request->feature_id);

        return redirect(route('saas.plans.index'))->with('success', 'Plan created successfully!');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('saas::plans.edit', [
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
        $plan = Plan::find($id);
        $plan->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->slug),
            'currency_code' => $request->currency_code,
            'price' => $request->price,
            'description' => $request->description,
            'period_unit' => $request->period_unit,
            'period_value' => $request->period_value,
            'status' => $request->status,
        ]);
        $plan->features()->sync($request->feature_id);

        return redirect(route('saas.plans.index'))->with('success', 'Plan updated successfully!');
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
