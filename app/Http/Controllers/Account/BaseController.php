<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

abstract class BaseController extends Controller
{
    protected $organization;

    public function __construct()
    {
        $this->organization = Auth::user()->organization;
    }
}
