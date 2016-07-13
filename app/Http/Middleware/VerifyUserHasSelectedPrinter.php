<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserHasSelectedPrinter
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
        if (! $request->session()->has('printer')) {
            $request->session()->put('url.intended', $request->fullUrl());
            return redirect()->route('printer.index');
        }
        
        return $next($request);
    }
}
