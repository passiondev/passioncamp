<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use App\Organization;
use Illuminate\Http\Request;
use App\Jobs\Order\AddToMailChimp;
use Illuminate\Support\Facades\Log;
use App\Jobs\Order\SendConfirmationEmail;
use App\Http\Requests\RegisterCreateRequest;

class RegisterController extends Controller
{
    protected $organization;
    protected $ticket_price;
    protected $can_pay_deposit;

    public function __construct()
    {
        $this->organization = Organization::whereSlug('pcc')->firstOrFail();
        $this->ticket_price = $this->getCurrentTicketPrice();
        $this->can_pay_deposit = now()->lte(Carbon::parse('2018-05-03')->endOfDay());
    }

    public function getCurrentTicketPrice()
    {
        // if (strtolower(request('code')) == 'rising') {
        //     return 365;
        // }

        if (now()->gte(Carbon::parse('2018-05-24')->startOfDay()) && now()->lte(Carbon::parse('2018-05-24')->endOfDay())) {
            return 375;
        }

        $prices = [
            '375' => '2018-01-01',
            '400' => '2018-04-08',
            '420' => '2018-05-06',
        ];

        return collect($prices)->filter(function ($date) {
            return now()->gte(Carbon::parse($date)->endOfDay());
        })->keys()->sort()->last();
    }

    public function create()
    {
        if (now()->gte(Carbon::parse('2018-06-03')->endOfDay())) {
            return view('register.closed');
        }

        return view('register.create', [
            'ticketPrice' => $this->ticket_price,
            'can_pay_deposit' => $this->can_pay_deposit,
        ]);
    }

    public function store(RegisterCreateRequest $request)
    {
        if ($this->ticket_price > 375 && request('num_tickets') >= 2) {
            $this->ticket_price = $this->ticket_price - 20;
        }

        \DB::beginTransaction();

        $order = $request
            ->forOrganization($this->organization)
            ->withTicketPrice($this->ticket_price)
            ->persist();

        try {
            $charge = \Stripe\Charge::create(
                [
                    'amount' => $this->can_pay_deposit && request('payment_type') == 'deposit' ? $order->deposit_total : $order->grand_total,
                    'currency' => 'usd',
                    'source' => request('stripeToken'),
                    'description' => 'Passion Camp',
                    'statement_descriptor' => 'PCC Students',
                    'metadata' => [
                        'order_id' => $order->id,
                        'email' => $order->user->person->email,
                        'name' => $order->user->person->name,
                    ],
                ],
                [
                    'api_key' => config('services.stripe.secret'),
                    'stripe_account' => $this->organization->setting('stripe_user_id'),
                ]
            );
        } catch (\Exception $e) {
            \DB::rollback();
            Log::debug($e);

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

        SendConfirmationEmail::dispatch($order);
        AddToMailChimp::dispatch($order);

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
