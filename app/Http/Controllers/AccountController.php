<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('verify.auth.order');
        $this->middleware('redirect.to.order');
    }

    public function index()
    {
        return view('account.index');
    }
}
