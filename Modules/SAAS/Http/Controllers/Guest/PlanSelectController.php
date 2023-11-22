<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Plan;

class PlanSelectController extends Controller
{
    public function index()
    {
        return view('saas::guest.select-plan', [
            'plans' => Plan::active()->paginate(),
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
        $intent = auth()->user()->createSetupIntent();

        return view('saas::guest.subscribe', compact('plan', 'intent'));
    }

    public function confirm(Plan $plan)
    {
        return view('saas::guest.plan-confirm-form', [
            'plan' => $plan,
        ]);
    }
}
