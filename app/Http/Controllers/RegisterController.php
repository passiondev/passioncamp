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
use App\Jobs\Order\SendConfirmationEmail;

class RegisterController extends Controller
{
    protected $organization;
    protected $ticket_price = 370;

    public function __construct()
    {
        $this->organization = Organization::whereSlug('pcc')->firstOrFail();
        $this->ticket_price = $this->getCurrentTicketPrice();
    }

    public function getCurrentTicketPrice()
    {
        if (request('code') == 'rising') {
            return 370;
        }

        $prices = [
            // '370' => Carbon::parse('2017-01-01'),
            '360' => Carbon::parse('2017-04-03'),
            '390' => Carbon::parse('2017-04-27'),
            '410' => Carbon::parse('2017-05-07'),
        ];

        return collect($prices)->filter(function($date) {
            return Carbon::now()->gte($date);
        })->keys()->sort()->last();
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

        $user = User::firstOrCreate(['email' => request('contact.email')]);
        if (! count($user->person)) {
            $user->person()->associate(
                Person::create(array_collapse(request([
                    'contact.first_name',
                    'contact.last_name',
                    'contact.email',
                    'contact.phone',
                    'billing.street',
                    'billing.city',
                    'billing.state',
                    'billing.zip',
                ])))
            )->save();
        } else {
            $user->person->fill(array_collapse(request([
                'contact.first_name',
                'contact.last_name',
                'contact.email',
                'contact.phone',
                'billing.street',
                'billing.city',
                'billing.state',
                'billing.zip',
            ])))->save();
        }

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
                    'considerations',
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
                    'amount' => $order->grand_total,
                    'currency' => 'usd',
                    'source' => request('stripeToken'),
                    'description' => 'Passion Camp',
                    'statement_descriptor' => 'PCC SMMR CMP',
                    'metadata' => [
                        'order_id' => $order->id,
                        'email' => $user->person->email,
                        'name' => $user->person->name
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

        dispatch(new SendConfirmationEmail($order));

        return request()->expectsJson()
               ? $order->toArray()
               : redirect()->route('register.confirmation')->with('order_id', $order->id);
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
