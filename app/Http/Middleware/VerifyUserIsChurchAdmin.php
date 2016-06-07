<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserIsChurchAdmin
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
        if ($request->user() && $request->user()->isChurchAdmin()) {
            return $next($request);
        }

        if ($request->user() && $request->user()->isSuperAdmin()) {
            return redirect()->route('admin.organization.index');
        }

        return abort(401, 'Unauthorized.');
    }
}
