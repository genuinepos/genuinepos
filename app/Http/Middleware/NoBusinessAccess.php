<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NoBusinessAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $generalSettings = config('generalSettings');
        if ($generalSettings['subscription']->has_business == BooleanType::False->value && Auth::user()->branch_id == null) {

            Auth::guard()->logout(Auth::user());

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()->back();
        }
        return $next($request);
    }
}
