<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class changeLocationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $generalSettings = config('generalSettings');
        if ((!isset($generalSettings) && !isset($generalSettings['subscription'])) || !auth()?->user()) {
            
            Auth::guard()->logout();
            return redirect()->back();
        }

        if (
            !Session::get('chooseBusinessOrShop') &&
            auth()->user()->can('has_access_to_all_area') &&
            auth()->user()->is_belonging_an_area == BooleanType::False->value &&
            (
                $generalSettings['subscription']->has_business == BooleanType::True->value ||
                $generalSettings['subscription']->current_shop_count > 1
            )
        ) {
            // dd(auth()->user());
            return redirect()->route('change.location.index');
        }

        return $next($request);
    }
}
