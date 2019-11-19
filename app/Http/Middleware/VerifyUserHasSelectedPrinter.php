<?php

namespace App\Http\Middleware;

use Closure;

class VerifyUserHasSelectedPrinter
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
        if (!$request->session()->has('printer')) {
            $request->intended(url()->previous());

            return redirect()->route('printers.index');
        }

        return $next($request);
    }
}
