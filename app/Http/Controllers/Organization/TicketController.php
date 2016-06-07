<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function index(Organization $organization)
    {
        $tickets = $organization->attendees()->paginate(15);

        return view('admin.organization.ticket.index', compact('tickets'))->withOrganization($organization);
    }
}
