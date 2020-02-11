<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Lab404\Impersonate\Services\ImpersonateManager;

class ImpersonationController extends Controller
{
    public function impersonate(ImpersonateManager $impersonateManager, User $user)
    {
        $this->authorize('impersonate', $user);

        $impersonateManager->take(Auth::user(), $user);

        return redirect('/');
    }

    public function stopImpersonating(ImpersonateManager $impersonateManager)
    {
        $organization = Auth::user()->organization;

        $impersonateManager->leave();

        return $organization
            ? redirect()->route('admin.organizations.show', $organization)
            : redirect()->action('Super\UserController@index');
    }
}
