<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticket;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->active()
            ->get();

        return view('user.dashboard', [
            'user' => auth()->user(),
            'tickets' => $tickets,
        ]);
    }
}
