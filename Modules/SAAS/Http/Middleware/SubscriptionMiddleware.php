<?php

namespace Modules\SAAS\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validSubscription = false;
        $subscription = DB::connection('mysql')->table('subscriptions')
            ->where('tenant_id', tenant('id'))
            ->orderByDesc('end_time')
            ->select('end_time')
            ->first();
        if(isset($subscription->end_time)) {
            $validSubscription = Carbon::parse($subscription->end_time)->gte(now());
            if($validSubscription) {
                return $next($request);
            }
        }
        abort_if(! $validSubscription, 403, 'You do not have a active subscription for this domain');
    }
}
