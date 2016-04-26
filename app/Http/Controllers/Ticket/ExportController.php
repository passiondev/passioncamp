<?php

namespace App\Http\Controllers\Ticket;

use League\Csv\Writer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TicketRepository;

class ExportController extends Controller
{
    protected $tickets;

    public function __construct(TicketRepository $tickets)
    {
        $this->tickets = $tickets;
    }

    public function index()
    {
        $tickets = $this->tickets
                   ->forUser(Auth::user())
                   ->with('person', 'order.user.person')
                   ->get();

        $tickets = $tickets->active()->map(function ($ticket) {
            $data = [];
            $data = [
                'id'         => $ticket->id,
                'order id'   => $ticket->order_id,
                'created at' => (string) $ticket->created_at,

                'type' => $ticket->agegroup,
                'first name' => $ticket->person->first_name,
                'last name'  => $ticket->person->last_name,
                'gender' => $ticket->person->gender,
                'grade' => $ticket->person->grade,
                'special considerations' => $ticket->person->allergies,
            ];

            if (Auth::user()->is_super_admin || Auth::user()->organization->slug == 'pcc') {
                $data += [
                    'email'      => $ticket->person->email,
                    'phone'      => $ticket->person->email,
                    'birthdate' => (string) $ticket->person->birthdate,
                    'shirt size' => $ticket->shirtsize,
                    'school' => $ticket->school,
                    'roommate requested' => $ticket->roommate_requested,
                ];
            }

            $data += [
                'contact first name' => $ticket->order->user->person->first_name,
                'contact last name' => $ticket->order->user->person->last_name,
                'contact email' => $ticket->order->user->person->email,
                'contact phone' => $ticket->order->user->person->phone,
            ];
        });

        //we create the CSV into memory
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($tickets->first()));
        $csv->insertAll($tickets->all());

        $csv->output('tickets.csv');
    }
}
