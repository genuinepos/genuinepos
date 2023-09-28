<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Modules\SAAS\Entities\Plan;
use Illuminate\Routing\Controller;

class PlanSelectController extends Controller
{
    public function index()
    {
        return view('saas::guest.select-plan', [
            'plans' => Plan::where('status', 1)->paginate(),
        ]);
    }

    public function show(Plan $plan)
    {
        return view('saas::guest.plan-detail', [
            'plan' => $plan,
        ]);
    }

    public function subscribe(Request $request, Plan $plan)
    {
        return view('saas::guest.subscribe', [
            'plan' => $plan,
        ]);
    }
}
