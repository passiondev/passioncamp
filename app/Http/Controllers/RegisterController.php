<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use App\OrderItem;
use Carbon\Carbon;
use Omnipay\Omnipay;
use App\Organization;
use App\Http\Requests;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    protected $organization;
    protected $ticket_price = 370;

    public function __construct()
    {
        if (Carbon::now()->gte(Carbon::create(2016, 5, 2, 4))) {
            $this->ticket_price = 390;
        }
        if (Carbon::now()->gte(Carbon::create(2016, 6, 13, 4))) {
            $this->ticket_price = 410;
        }

        $this->organization = Organization::findOrFail(8);
    }

    public function create()
    {
        return view('register.create')->withTicketPrice($this->ticket_price);
    }

    public function store(Request $request)
    {
        if ($request->num_tickets >= 2) {
            $this->ticket_price -= 20;
        }

        $this->validate($request, [
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'num_tickets' => 'required|numeric|min:1',
            'tickets.*.first_name' => 'required',
            'tickets.*.last_name' => 'required',
            'tickets.*.shirtsize' => 'required',
            'tickets.*.gender' => 'required',
            'tickets.*.grade' => 'required',
            'tickets.*.birthdate' => 'required',
        ]);

        $user = User::firstOrNew([
            'email' => $request->contact['email']
        ]);
        if (! $user->exists) {
            $person = Person::create($request->contact);
            $user->person()->associate($person);
            $user->save();
        }
        if ($user->access === null) {
            event(new UserCreated($user));
        }
        
        $order = new Order;
        $order->organization_id = $this->organization->id;
        $order->user()->associate($user);
        $order->save();

        // record donation
        $donation_total = $request->fund_amount == 'other' ? $request->fund_amount_other : $request->fund_amount;
        if ($donation_total > 0) {
            $item = new OrderItem;
            $item->order()->associate($order);
            $item->organization_id = $this->organization->id;
            $item->type = 'donation';
            $item->price = $donation_total;
            $item->save();
        }

        // record tickets
        collect($request->tickets)->each(function ($data) use ($order) {
            $person = Person::create(array_only($data, [
                'first_name',
                'last_name',
                'email',
                'phone',
                'birthdate',
                'gender',
                'grade',
                'allergies',
            ]));

            $ticket_data = array_only($data, [
                'school',
                'shirtsize',
                'roommate_requested',
                'location'
            ]);

            $ticket = new Ticket;
            $ticket->agegroup = 'student';
            $ticket->organization_id = $this->organization->id;
            $ticket->order()->associate($order);
            $ticket->person()->associate($person);
            $ticket->ticket_data = $ticket_data;
            $ticket->price = $this->ticket_price;
            $ticket->save();
        });

        // generate any remaining tickets
        $remaining_ticket_count = $request->num_tickets - $order->tickets->count();
        if ($remaining_ticket_count) {
            foreach (range(1, $remaining_ticket_count) as $i) {
                $ticket = new Ticket;
                $ticket->type = 'ticket';
                $ticket->organization_id = $this->organization->id;
                $ticket->order()->associate($order);
                $ticket->person()->associate(Person::create());
                $ticket->price = $ticket_price;
                $ticket->save();
            }
            $order->load('tickets');
        }
        
        // deposit or full amount
        $payment_amount = $order->grand_total;
        if ($request->payment_amount_type == 'deposit') {
            $payment_amount = 60 * $order->tickets->count();
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $charge = \Stripe\Charge::create(array(
              'amount' => $payment_amount * 100,
              'currency' => 'usd',
              'source' => $request->stripeToken,
              'description' => 'Passion Camp',
              'metadata' => ['order_id' => $order->id, 'email' => $order->user->person->email, 'name' => $order->user->person->name]
            ), array('stripe_account' => $this->organization->setting('stripe_user_id')));
        } catch (\Exception $e) {
            return redirect()->route('register.create')->withInput()->with('error', $e->getMessage());
        }

        // Add payment to order
        $order->addTransaction([
            'source' => 'stripe',
            'processor_transactionid' => $charge->id,
            'amount' => $charge->amount / 100,
            'card_type' => $charge->source->brand,
            'card_num' => $charge->source->last4,
        ]);


        // dispatch confirmation email job
        \Mail::send('emails.order.confirmation', compact('order'), function ($m) use ($order) {
            $m->from('students@passioncitychurch.com', 'PCC Students');
            $m->to($order->user->person->email, $order->user->person->name);
            $m->subject('SMMR CMP Confirmation');
        });

        return redirect()->route('register.confirmation')->with('order_id', $order->id);
    }

    public function confirmation(Request $request)
    {
        if (! $request->session()->has('order_id')) {
            return redirect()->route('register.create');
        }

        $order = Order::findOrFail($request->session()->get('order_id'));

        return view('register.confirmation')->withOrder($order);
    }
}
