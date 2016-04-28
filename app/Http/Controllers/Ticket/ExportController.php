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
        $tickets = $this->tickets->forUser(Auth::user())
                   ->active()
                   ->with('person', 'order.user.person', 'waiver')
                   ->get();

        if ($tickets->count() == 0) {
            abort(404, 'No tickets to export.');
        }

        $tickets = $tickets->active()->map(function ($ticket) {
            $data = [
                'id'         => $ticket->id,
                'order id'   => $ticket->order_id,
                'created at' => (string) $ticket->created_at,
            ];

            if (Auth::user()->is_super_admin) {
                $data += [
                    'church' => $ticket->organization->church->name
                ];
            }

            $data += [
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
                    'phone'      => $ticket->person->phone,
                    'birthdate' => (string) $ticket->person->birthdate,
                    'shirt size' => $ticket->shirtsize,
                    'school' => $ticket->school,
                    'roommate requested' => $ticket->roommate_requested,
                ];
            }

            $data += [
                'waiver status' => $ticket->waiver ? $ticket->waiver->status : '',
                'contact first name' => $ticket->order->user->person->first_name,
                'contact last name' => $ticket->order->user->person->last_name,
                'contact email' => $ticket->order->user->person->email,
                'contact phone' => $ticket->order->user->person->phone,
            ];

            return $data;
        });

        //we create the CSV into memory
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($tickets->first()));
        $csv->insertAll($tickets->all());

        $csv->output('tickets.csv');
    }
}
