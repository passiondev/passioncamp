<?php

namespace App\Auth;

use App\User;
use Illuminate\Http\Request;

class MagicAuthentication
{
    protected $request;

    protected $identifier = 'email';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function requestLink()
    {
        $user = User::where($this->identifier, $this->identifierValue())->firstOrFail();

        $user->createMagicToken()->sendMagicLink([
            'remember' => $this->request->has('remember'),
            $this->identifier => $this->identifierValue(),
        ]);
    }

    public function identifierValue()
    {
        return $this->request->input($this->identifier);
    }
}
