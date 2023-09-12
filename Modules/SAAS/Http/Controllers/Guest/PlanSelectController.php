<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SAAS\Entities\Plan;

class PlanSelectController extends Controller
{
    public function index()
    {
        return view('saas::guest.select-plan', [
            'plans' => Plan::where('status', 1)->paginate(),
        ]);
    }

    public function show(string $slug)
    {
        return view('saas::guest.plan-detail', [
            'plan' => Plan::whereSlug($slug)->first(),
        ]);
    }
}
