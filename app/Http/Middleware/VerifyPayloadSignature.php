<?php

namespace App\Http\Middleware;

use Closure;
use App\RoutePayloadSignature;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class VerifyPayloadSignature
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
        if ($request['signature'] !== RoutePayloadSignature::create($request->route('payload'))) {
            throw new ResourceNotFoundException();
        }

        return $next($request);
    }
}
