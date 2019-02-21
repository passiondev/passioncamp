<?php

namespace App\Http\Controllers;

use App\Occurrence;
use Illuminate\Http\Request;

class TicketPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:60,1');
    }

    public function __invoke()
    {
        return (new Occurrence('pcc'))->ticketPrice(request('num_tickets'), request('code'));
    }
}
