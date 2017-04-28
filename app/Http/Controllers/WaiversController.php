<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;

class WaiversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->with('person', 'waiver', 'order.organization', 'order.user.person')
            ->active()
            ->join('people', 'order_items.person_id', '=', 'people.id')
            ->select('order_items.*')
            ->orderBy('people.last_name')
            ->orderBy('people.first_name')
            ->get();

        return view('waivers.index', compact('tickets'));
    }
}
