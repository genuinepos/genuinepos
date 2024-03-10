<?php

namespace App\Http\Middleware;

use App\Enums\BooleanType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Enums\SubscriptionPaymentStatus;
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

        if (
            $subscription->has_business == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            ($subscription->is_completed_business_startup == BooleanType::False->value && $subscription->is_completed_branch_startup == BooleanType::False->value)
        ) {

            Session::put('startupType', 'business_and_branch');
            return redirect()->route('setup.startup.form');
        } else if (
            $subscription->has_business == BooleanType::False->value &&
            auth()->user()->can('has_access_to_all_area') &&
            $subscription->is_completed_branch_startup == BooleanType::False->value
        ) {

            Session::put('startupType', 'branch');
            return redirect()->route('setup.startup.form');
        } else if (
            $subscription->has_business == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $subscription->is_completed_business_startup == BooleanType::False->value &&
                $subscription->is_completed_branch_startup == BooleanType::True->value
            )
        ) {

            Session::put('startupType', 'business');
            return redirect()->route('setup.startup.form');
        } else if (
            $subscription->has_business == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            ($subscription->is_completed_business_startup == BooleanType::False->value && $subscription->is_completed_branch_startup == BooleanType::True->value)
        ) {

            Session::put('startupType', 'business');
            return redirect()->route('setup.startup.form');
        } else if (
            $subscription->has_business == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $subscription->is_completed_business_startup == BooleanType::False->value && $subscription->is_completed_branch_startup == BooleanType::True->value
            )
        ) {

            Session::put('startupType', 'business_and_branch');
            return redirect()->route('setup.startup.form');
        } else if (
            $subscription->has_business == BooleanType::False->value &&
            auth()->user()->can('has_access_to_all_area') &&
            $subscription->is_completed_branch_startup == BooleanType::False->value
        ) {

            Session::put('startupType', 'branch');
            return redirect()->route('setup.startup.form');
        }

        if ($subscription->is_trial_plan == BooleanType::True->value) {

            $trialExpireDate = $this->getTrialExpireDate($subscription->trial_start_date, $subscription->trial_days);

            if (date('Y-m-d') > date('Y-m-d', strtotime($trialExpireDate))) {

                return redirect()->route('software.service.billing.upgrade.plan.index')->with(['trialExpireDate' => __('Your trial period is expired. Please Upgrade your plan.')]);
            }
        } elseif (
            $subscription->initial_payment_status == SubscriptionPaymentStatus::Due->value &&
            $generalSettings['subscription']->initial_plan_expire_date &&
            date('Y-m-d') > $generalSettings['subscription']->initial_plan_expire_date
        ) {

            return redirect()->route('software.service.billing.due.repayment')->with(['duePayment' => __('Please Repayment your due amount.')]);
        } elseif (
            auth()->user()?->branch &&
            auth()->user()?->branch?->expire_date &&
            date('Y-m-d') > auth()->user()?->branch?->expire_date
        ) {

            if (auth()->user()->can('billing_renew_branch')) {

                return redirect()->route('software.service.billing.cart.for.renew.branch')->with(['branchExpired' => __('Shop is expired please renew the shop')]);
            } else {

                auth()->logout();
                return redirect()->back()->with('branchExpired', __('Shop is expired. Please contact your Business/Authority'));
            }
        } elseif (
            !auth()->user()?->branch_id &&
            date('Y-m-d') > $generalSettings['subscription']->business_expire_date
        ) {
            if (auth()->user()->can('billing_renew_branch')) {

                return redirect()->route('software.service.billing.cart.for.renew.branch')->with(['businessExpired' => __('Business is expired please renew your business')]);
            } else {

                auth()->logout();
                return response()->json(['businessExpired' => __('Shop is expired. Please contact your Business/Authority')]);
            }
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
