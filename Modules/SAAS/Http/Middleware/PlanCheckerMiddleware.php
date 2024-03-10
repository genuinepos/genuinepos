<?php

namespace Modules\SAAS\Http\Middleware;

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

        if($tenant->haveExpired()) {

            return redirect()->route('saas.plan.all', ['error' => 'plan-expired']);
        }

        $tenantCreatedAt = Carbon::parse($tenant->created_at);
        $isVerified = isset($tenant->is_verified) && ($tenant->is_verified == 1);
        $enjoyedTrialDays = today()->diffInDays($tenantCreatedAt);
        $minTrialDays = config('saas.trial_min_days');
        $maxTrialDays = config('saas.trial_max_days');

        if ($enjoyedTrialDays > $minTrialDays && $enjoyedTrialDays <= $maxTrialDays) {

            if (! $isVerified) {

                return redirect()->route('verification.notice')->with('error', __('Verify your Business Email to continue'));
            }
        } elseif ($enjoyedTrialDays > $maxTrialDays) {

            return redirect()->route('saas.plan.all')->with('error', __('Purchase a plan to continue'));
        }

        return $next($request);
    }
}
