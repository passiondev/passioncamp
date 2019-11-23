<?php

namespace App\Http\Middleware;

use Closure;

class Closed
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
        if ($request->user()->organization && 'pcc' == $request->user()->organization->slug) {
            return $next($request);
        }

        if ($request->session()->has('spark:impersonator')) {
            return $next($request);
        }

        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        return redirect('/closed');
    }
}
