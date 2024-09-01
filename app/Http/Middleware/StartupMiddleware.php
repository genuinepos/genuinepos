<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class StartupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $generalSettings = config('generalSettings');

        if (
            $generalSettings['subscription__has_business'] == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $generalSettings['subscription__is_completed_business_setup'] == BooleanType::False->value &&
                $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::False->value
            )
        ) {

            Session::put('startupType', 'business_and_branch');
            return redirect()->route('startup.form');
        } else if (
            $generalSettings['subscription__has_business'] == BooleanType::False->value &&
            auth()->user()->can('has_access_to_all_area') &&
            $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::False->value
        ) {

            Session::put('startupType', 'branch');
            return redirect()->route('startup.form');
        } else if (
            $generalSettings['subscription__has_business'] == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $generalSettings['subscription__is_completed_business_setup'] == BooleanType::False->value &&
                $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::True->value
            )
        ) {

            Session::put('startupType', 'business');
            return redirect()->route('startup.form');
        } else if (
            $generalSettings['subscription__has_business'] == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $generalSettings['subscription__is_completed_business_setup'] == BooleanType::False->value &&
                $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::True->value
            )
        ) {

            Session::put('startupType', 'business');
            return redirect()->route('startup.form');
        } else if (
            $generalSettings['subscription__has_business'] == BooleanType::True->value &&
            auth()->user()->can('has_access_to_all_area') &&
            (
                $generalSettings['subscription__is_completed_business_setup'] == BooleanType::False->value &&
                $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::True->value
            )
        ) {

            Session::put('startupType', 'business');
            return redirect()->route('startup.form');
        } else if (
            $generalSettings['subscription__has_business'] == BooleanType::False->value &&
            auth()->user()->can('has_access_to_all_area') &&
            $generalSettings['subscription__is_completed_branch_startup'] == BooleanType::False->value
        ) {

            Session::put('startupType', 'branch');
            return redirect()->route('startup.form');
        }

        return $next($request);
    }
}
