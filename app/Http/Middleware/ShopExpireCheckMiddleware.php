<?php

namespace App\Http\Middleware;

use App\Models\Setups\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopExpireCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $branch = Branch::find(auth()->user()->branch_id);
        if(isset($branch)) {
            $expired = today()->gt($branch->expire_at);
            if($expired) {
                // The shop is eb xpired -> redirect to plan renewal page
                return redirect()->route('saas.plan.all');
            }
        }
        return $next($request);
    }
}
