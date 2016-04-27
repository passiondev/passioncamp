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
        if ($request->user() && $request->user()->organization_id) {
            return $next($request);
        }

        if ($request->user() && $request->user()->is_super_admin) {
            return redirect()->route('admin.organization.index');
        }

        return response('Unauthorized.', 401);
    }
}
