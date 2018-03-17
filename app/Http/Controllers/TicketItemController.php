<?php

namespace App\Http\Controllers;

use App\Item;

class TicketItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index()
    {
        $items = Item::whereType('ticket')
            ->withPurchasedSum()
            ->get();

        return view('ticket-items.index', compact('items'));
    }
}
