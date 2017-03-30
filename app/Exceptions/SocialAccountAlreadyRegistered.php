<?php

namespace App\Exceptions;

use \RuntimeException;

class SocialAccountAlreadyRegistered extends RuntimeException
{
    public static function for($provider)
    {
        return new static(sprintf('A user has already been registered with your %s account.', ucfirst($provider)));
    }
}
