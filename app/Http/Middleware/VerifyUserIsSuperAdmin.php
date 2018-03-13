<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserIsSuperAdmin
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
        if ($request->user() && $request->user()->isSuperAdmin()) {
            return $next($request);
        }

        return abort(403, 'Unauthorized.');
    }
}
