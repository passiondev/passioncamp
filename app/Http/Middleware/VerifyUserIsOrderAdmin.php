<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserIsOrderAdmin
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
        if ($request->user() && $request->user()->isOrderOwner()) {
            return $next($request);
        }

        return abort(401, 'Unauthorized.');
    }
}
