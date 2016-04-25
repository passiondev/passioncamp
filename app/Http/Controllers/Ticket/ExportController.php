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
            return [
                'id'         => $ticket->id,
                'order id'   => $ticket->order_id,
                'created at' => (string) $ticket->created_at,

                'first name' => $ticket->person->first_name,
                'last name'  => $ticket->person->last_name,
                'email'      => $ticket->person->email,
                'phone'      => $ticket->person->email,
                'birthdate' => (string) $ticket->person->birthdate,
                'gender' => $ticket->person->gender,
                'shirt size' => $ticket->shirt_size,
                'grade' => $ticket->person->grade,
                'school' => $ticket->school,
                'allergies' => $ticket->person->allergies,
                'roomate requested' => $ticket->roommate_requested,
                
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
