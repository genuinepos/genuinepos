<?php

namespace Modules\SAAS\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\Plan;
use Modules\SAAS\Entities\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request, Plan $plan) : ?Subscription
    {
        $subscription = Subscription::create([
            'plan_id' => $plan->id,
            'user_id' => auth()->user()->id,
            // 'payment_id' => $request->payment_id,
            // 'domain' => null,
            'start_time' => now(),
            'end_time' => match($plan->preiod_unit) {
                'day' => now()->addDays($plan->period_value),
                'year' => now()->addYears($plan->period_value),
                default => now()->addMonths($plan->period_value),
            },
        ]);

        if(isset($subscription)) {
            // Send email notification & other staff
            return $subscription;
        }
    }
}