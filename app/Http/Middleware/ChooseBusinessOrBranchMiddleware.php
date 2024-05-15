<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ChooseBusinessOrBranchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !Session::get('chooseBusinessOrShop') &&
            auth()->user()->can('has_access_to_all_area') &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            (
                config('generalSettings')['subscription']->has_business == BooleanType::True->value ||
                config('generalSettings')['subscription']->current_shop_count > 1
            )
        ) {

            return redirect()->route('change.business.branch.location.index');
        }

        return $next($request);
    }
}
