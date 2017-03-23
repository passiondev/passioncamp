<?php

namespace App\Http\Middleware;

use Closure;

class VerifyTicketCanBeAddedToOrganization
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
        if ($request->user()->organization->tickets_remaining_count > 0) {
            return $next($request);
        }

        return $request->expectsJson()
                ? response('Cannot add more attendees than tickets purchased.', 402)
                : redirect()->action('Account\DashboardController')->withError('Cannot add more attendees than tickets purchased.');

    }
}
