<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use App\OrderItem;
use Omnipay\Omnipay;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store(Request $request)
    {
        $ticket_price = 370;
        if ($request->num_tickets >= 2) {
            $ticket_price -= 20;
        }

        $this->validate($request, [
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'num_tickets' => 'required|numeric|min:1',
            'tickets.*.first_name' => 'required',
            'tickets.*.last_name' => 'required',
            'tickets.*.shirt_size' => 'required',
            'tickets.*.gender' => 'required',
            'tickets.*.grade' => 'required',
            'tickets.*.birthdate' => 'required',
        ]);

        $person = Person::create($request->contact);

        $user = new User;
        $user->person()->associate($person);
        $user->save();
        
        $order = new Order;
        $order->organization_id = 8;
        $order->user()->associate($user);
        $order->save();

        // record donation
        $donation_total = $request->fund_amount == 'other' ? $request->fund_amount_other : $request->fund_amount;
        if ($donation_total > 0) {
            $item = new OrderItem;
            $item->order()->associate($order);
            $item->organization_id = $order->organization_id;
            $item->type = 'donation';
            $item->price = $donation_total;
            $item->save();
        }

        // record tickets
        collect($request->tickets)->each(function ($data) use ($order, $ticket_price) {
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
            $ticket->organization_id = $order->organization_id;
            $ticket->order()->associate($order);
            $ticket->person()->associate($person);
            $ticket->ticket_data = $ticket_data;
            $ticket->price = $ticket_price;
            $ticket->save();
        });

        // generate any remaining tickets
        $remaining_ticket_count = $request->num_tickets - $order->tickets->count();
        if ($remaining_ticket_count) {
            foreach (range(1, $remaining_ticket_count) as $i) {
                $ticket = new Ticket;
                $ticket->type = 'ticket';
                $ticket->organization_id = $order->organization_id;
                $ticket->order()->associate($order);
                $ticket->person()->associate(Person::create());
                $ticket->price = $ticket_price;
                $ticket->save();
            }
            $order->load('tickets');
        }
        
        // make and record payment
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey(config('services.stripe.pcc.secret'));

        // deposit or full amount
        $payment_amount = $order->grand_total;
        if ($request->payment_amount_type == 'deposit') {
            $payment_amount = 60 * $order->tickets->count();
        }

        $charge = $gateway->purchase([
            'amount' => number_format($payment_amount, 2, '.', ''),
            'currency' => 'USD',
            'token' => $request->stripeToken,
            'description' => 'Passion Camp',
            'metadata' => ['order_id' => $order->id, 'email' => $order->user->person->email, 'name' => $order->user->person->name]
        ])->send();

        if (! $charge->isSuccessful()) {
            // DB::rollback();
            return redirect()->route('register.create')->withInput()->with('error', $charge->getMessage());
        }
        // Add payment to order
        $order->addTransaction([
            'source' => 'stripe',
            'processor_transactionid' => $charge->getTransactionReference(),
            'amount' => $charge->getData()['amount'] / 100,
            'card_type' => $charge->getSource()['brand'],
            'card_num' => $charge->getSource()['last4'],
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
