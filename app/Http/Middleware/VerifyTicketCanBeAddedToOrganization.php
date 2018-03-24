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
        if ($request->user()->organization->canAddTickets()) {
            return $next($request);
        }

        return $request->expectsJson()
                ? response('Cannot add more attendees than tickets purchased.', 402)
                : redirect('/')->withError('Cannot add more attendees than tickets purchased.');
    }
}
