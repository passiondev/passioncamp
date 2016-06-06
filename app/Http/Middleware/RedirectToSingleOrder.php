<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToSingleOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->isOrderOwner() && Auth::user()->has('orders') && Auth::user()->orders->count() == 1) {
            return redirect()->route('order.show', Auth::user()->orders->first());
        }

        return $next($request);
    }
}
