<?php

namespace App\Http\Controllers;

use App\Item;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyUserIsSuperAdmin;

class TicketItemController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, VerifyUserIsSuperAdmin::class]);
    }

    public function index()
    {
        $items = Item::whereType('ticket')
            ->withPurchasedSum()
            ->get();

        return view('ticket-items.index', compact('items'));
    }
}
