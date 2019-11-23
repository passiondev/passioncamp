<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToSingleOrder
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->isOrderOwner() && Auth::user()->has('orders') && 1 == Auth::user()->orders->count()) {
            return redirect()->route('order.show', Auth::user()->orders->first());
        }

        return $next($request);
    }
}
