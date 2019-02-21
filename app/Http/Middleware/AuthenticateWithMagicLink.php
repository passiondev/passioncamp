<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class AuthenticateWithMagicLink extends Middleware
{
    protected function redirectTo($request)
    {
        return route('magic.login');
    }
}
