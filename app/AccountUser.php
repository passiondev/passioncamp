<?php

namespace App;

class AccountUser
{
	public $organization;
	public $user;

    public function __construct(Organization $organization, User $user = null)
    {
        $this->organization = $organization;
        $this->user = $user;
    }
}
