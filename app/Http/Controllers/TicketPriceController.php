<?php

namespace App\Http\Controllers;

use App\Occurrence;
use App\Organization;
use Illuminate\Http\Request;

class TicketPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:60,1');
    }

    public function __invoke()
    {
        $organization = Organization::whereSlug(request('event'))->firstOrFail();
        $occurrence = new Occurrence(config('occurrences.' . request('event')));

        return $occurrence->ticketPrice(request('num_tickets'), request('code'));
    }
}
