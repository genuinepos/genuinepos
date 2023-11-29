<?php

namespace Modules\SAAS\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Exceptions\TenancyNotInitializedException;

class PlanCheckerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant();
        if (! $tenant) {
            throw new TenancyNotInitializedException;
        }

        $tenantCreatedAt = Carbon::parse($tenant->created_at);
        $isVerified = isset($tenant->is_verified) && ($tenant->is_verified == 1);
        $enjoyedTrialDays = today()->diffInDays($tenantCreatedAt);

        if($enjoyedTrialDays > 3 && $enjoyedTrialDays <= 7) {
            // Send to verification page
            if(! $isVerified) {
                return redirect()->route('saas.plan.confirm', ['plan' =>'trial'])->with('error', __('Purchase a plan to continue'));
            }
            // Send to payment page
            return redirect()->route('saas.plan.confirm', ['plan' =>'trial'])->with('error', __('Purchase a plan to continue'));
        }
        return $next($request);
    }
}
