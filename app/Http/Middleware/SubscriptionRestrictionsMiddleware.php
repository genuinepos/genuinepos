<?php

namespace App\Http\Middleware;

use App\Enums\SubscriptionPaymentStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionRestrictionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $generalSettings = config('generalSettings');
        $subscription = $generalSettings['subscription'];

        if ($subscription->is_trial_plan == 1) {

            $trialExpireDate = $this->getTrialExpireDate($subscription->trial_start_date, $subscription->trial_days);

            if (date('Y-m-d') > date('Y-m-d', strtotime($trialExpireDate))) {

                return redirect()->route('software.service.billing.upgrade.plan')->with(['trialExpireDate' => __('Your trial period is expired. Please Upgrade your plan.')]);
            }
        } elseif (
            $subscription->initial_payment_status == SubscriptionPaymentStatus::Due->value &&
            $generalSettings['subscription']->initial_plan_expire_date &&
            date('Y-m-d') > $generalSettings['subscription']->initial_plan_expire_date
        ) {

            return redirect()->route('software.service.billing.due.repayment')->with(['duePayment' => __('Please Repayment you due amount.')]);
        } else if ($subscription->is_completed_startup == 0) {

            return redirect()->route('setup.startup.form');
        }

        return $next($request);
    }

    function getTrialExpireDate($startDate, $trialDays)
    {
        $startDate = new \DateTime($startDate);
        $endDate = clone $startDate;
        // Add 7 days to today's date
        $lastDate = $endDate->modify('+1 ' . $trialDays . ' days');
        // $lastDate = $lastDate->modify('+1 days');

        return $lastDate->format('Y-m-d');
    }
}
