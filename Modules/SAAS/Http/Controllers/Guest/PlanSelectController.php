<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Modules\SAAS\Entities\Plan;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Currency;

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

    public function confirm(Plan $plan, $pricePeriod = null)
    {
        $currencies = Currency::select('id', 'country', 'currency', 'code')->get();
        return view('saas::guest.plan-confirm-form', [
            'plan' => $plan,
            'pricePeriod' => $pricePeriod,
            'currencies' => $currencies,
        ]);
    }
}
