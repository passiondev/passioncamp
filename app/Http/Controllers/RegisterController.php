<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use App\OrderItem;
use Carbon\Carbon;
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
        $this->organization = Organization::whereSlug('pcc')->firstOrFail();
    }

    public function create()
    {
        return view('register.create')->withTicketPrice($this->ticket_price);
    }

    public function store()
    {
        if (request('num_tickets') >= 2) {
            $this->ticket_price -= 20;
        }

        $this->validate(request(), [
            'contact.first_name' => 'required',
            'contact.last_name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'billing.street' => 'required',
            'billing.city' => 'required',
            'billing.state' => 'required',
            'billing.zip' => 'required',
            'num_tickets' => 'required|numeric|min:1',
            'tickets.*.first_name' => 'required',
            'tickets.*.last_name' => 'required',
            'tickets.*.shirtsize' => 'required',
            'tickets.*.gender' => 'required',
            'tickets.*.grade' => 'required',
            'tickets.*.birthdate' => 'required',
        ]);

        \DB::beginTransaction();

        $user = User::firstOrCreate(['email' => request('contact.email')], [
            'person_id' => Person::create(array_collapse(request()->only([
                'contact.first_name',
                'contact.last_name',
                'contact.email',
                'contact.phone',
                'billing.street',
                'billing.city',
                'billing.state',
                'billing.zip',
            ])))->id
        ]);

        // if ($user->wasRecentlyCreated) {
        //     event(new UserCreated($user));
        // }

        $order = $user->orders()->create([
            'organization_id' => $this->organization->id,
        ]);

        // record donation
        $donation_total = request('fund_amount') == 'other' ? request('fund_amount_other') : request('fund_amount');
        if ($donation_total > 0) {
            $order->items()->create([
                'type' => 'donation',
                'organization_id' => $this->organization->id,
                'price' => $donation_total * 100,
            ]);
        }

        // record tickets
        collect(request('tickets'))->each(function ($data) use ($order) {
            $order->tickets()->create([
                'agegroup' => 'student',
                'ticket_data' => array_only($data, ['shirtsize', 'school', 'roommate_requested', 'travel_plans']),
                'price' => $this->ticket_price * 100,
                'organization_id' => $this->organization->id,
                'person_id' => Person::create(array_only($data, [
                    'first_name', 'last_name', 'email', 'phone',
                    'birthdate', 'gender', 'grade', 'allergies',
                ]))->id,
            ]);
        });

        while ($order->tickets()->count() < request('num_tickets')) {
            $order->tickets()->create([
                'agegroup' => 'student',
                'price' => $this->ticket_price * 100,
                'organization_id' => $this->organization->id,
                'person_id' => Person::create()->id,
            ]);
        }

        try {
            $charge = \Stripe\Charge::create(
                [
                    'amount' => request('payment_amount_type') == 'deposit' ? 6000 * $order->tickets->count() : $order->grand_total,
                    'currency' => 'usd',
                    'source' => request('stripeToken'),
                    'description' => 'Passion Camp',
                    'statement_descriptor' => 'PCC SMMR CMP',
                    'metadata' => [
                        'order_id' => $order->id,
                        'email' => $order->user->person->email,
                        'name' => $order->user->person->name
                    ]
                ],
                [
                    'api_key' => config('services.stripe.secret'),
                    'stripe_account' => $this->organization->setting('stripe_user_id'),
                ]
            );
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('register.create')->withInput()->with('error', $e->getMessage());
        }

        // Add payment to order
        $order->addTransaction([
            'source' => 'stripe',
            'identifier' => $charge->id,
            'amount' => $charge->amount,
            'cc_brand' => $charge->source->brand,
            'cc_last4' => $charge->source->last4,
        ]);

        \DB::commit();

        /**
         * TODO
         */
        // \Mail::send('emails.order.confirmation', compact('order'), function ($m) use ($order) {
        //     $m->from('students@passioncitychurch.com', 'PCC Students');
        //     $m->to($order->user->person->email, $order->user->person->name);
        //     $m->subject('SMMR CMP Confirmation');
        // });

        return redirect()->route('register.confirmation')->with('order_id', $order->id);
    }

    public function confirmation()
    {
        if (! session()->has('order_id')) {
            return redirect()->route('register.create');
        }

        $order = Order::findOrFail(session('order_id'));

        return view('register.confirmation')->withOrder($order);
    }
}
