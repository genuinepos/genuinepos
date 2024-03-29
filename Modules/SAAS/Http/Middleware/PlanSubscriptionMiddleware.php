<?php

namespace Modules\SAAS\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PlanSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validPlanSubscription = false;
        $subscription = DB::connection('mysql') // <-- landlord connection
            ->table('plan_subscriptions')
            ->where('tenant_id', tenant('id'))
            ->select('end_at')
            ->first();

        if (isset($subscription->end_time)) {
            
            $validPlanSubscription = Carbon::parse($subscription->end_time)->gte(now());
            if ($validPlanSubscription) {

                return $next($request);
            }
        }

        if (tenant('plan_id') !== null) {

            return redirect(config('app.url'));

            return redirect(route('saas.plan.subscribe', tenant('plan_id')));
        }

        return redirect(route('saas.plan.all'))->with('error', 'Select A Plan First!');
    }
}
