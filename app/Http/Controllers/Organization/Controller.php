<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use App\Http\Requests;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    protected $organization;

    protected function index() {}

    public function adminIndex(Organization $organization)
    {
        $this->organization = $organization;

        return $this->index();
    }

    public function accountIndex()
    {
        $this->organization = Auth::user()->organization;

        return $this->index();
    }
}
