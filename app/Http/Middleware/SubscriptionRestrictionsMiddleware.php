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

        if ($subscription->is_trial_plan == BooleanType::True->value) {

            $trialExpireDate = $this->getTrialExpireDate($subscription->trial_start_date, $subscription->trial_days);

            if (date('Y-m-d') > date('Y-m-d', strtotime($trialExpireDate))) {

                return redirect()->route('software.service.billing.upgrade.plan.index')->with(['trialExpireDate' => __('Your trial period is expired. Please Upgrade your plan.')]);
            }
        } elseif (
            $subscription->has_due_amount == SubscriptionPaymentStatus::Due->value &&
            $generalSettings['subscription']->due_repayment_date &&
            date('Y-m-d') > $generalSettings['subscription']->due_repayment_date
        ) {

            return redirect()->route('software.service.billing.due.repayment.index')->with(['duePayment' => __('Please Repayment your due amount.')]);
        } elseif (
            auth()->user()?->branch &&
            auth()->user()?->branch?->expire_date &&
            date('Y-m-d') > auth()->user()?->branch?->expire_date
        ) {

            if (auth()->user()->can('billing_renew_branch')) {

                return redirect()->route('software.service.billing.shop.renew.cart')->with(['branchExpired' => __('Shop is expired please renew the shop')]);
            } else {

                auth()->logout();
                return redirect()->back()->with('branchExpired', __('Shop is expired. Please contact your Authority'));
            }
        } elseif (
            !auth()->user()?->branch_id &&
            $generalSettings['subscription']->has_business == BooleanType::True->value &&
            date('Y-m-d') > $generalSettings['subscription']->business_expire_date
        ) {

            if (auth()->user()->can('billing_renew_branch')) {

                return redirect()->route('software.service.billing.shop.renew.cart')->with(['businessExpired' => __('Business is expired please renew your business')]);
            } else {

                auth()->logout();
                return response()->json(['businessExpired' => __('Shop is expired. Please contact your Authority')]);
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
