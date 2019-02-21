<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Occurrence;
use App\Organization;
use App\Registration;
use App\Mail\WaiverRequest;
use App\Billing\PaymentGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Billing\PaymentFailedException;
use App\Jobs\Order\SendConfirmationEmail;
use App\Http\Requests\RegisterCreateRequest;

class RegisterController extends Controller
{
    protected $organization;
    protected $can_pay_deposit;
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
        $this->can_pay_deposit = now()->lte(Carbon::parse('2018-05-03')->endOfDay());
    }

    public function create()
        $this->organization = Organization::whereSlug('pcc')->firstOrFail();
    {
        $organization = Organization::whereSlug(request()->input('event'))->firstOrFail();
        $occurrence = new Occurrence(config('occurrences.' . request()->input('event')));

        if ($occurrence->isClosed() && request()->query('code') !== 'wwknd2019') {
            return view('register.closed', ['occurrence' => $occurrence]);
        }

        return view('register.create', [
            'event' => request()->input('event'),
            'occurrence' => $occurrence,
            'ticketPrice' => $occurrence->ticketPrice() / 100,
            'can_pay_deposit' => false,
            'lowestTicketPrice' => $occurrence->lowestTicketPrice() / 100,
        ]);
    }

    public function store(RegisterCreateRequest $request)
    {
        $organization = Organization::whereSlug($request->input('event'))->firstOrFail();
        $occurrence = new Occurrence(config('occurrences.' . request()->input('event')));

        $user = User::firstOrCreate(
            [
                'email' => $request->input('contact.email'),
            ],
            [
                'person' => array_collapse($request->only([
                    'contact.first_name',
                    'contact.last_name',
                    'contact.email',
                    'contact.phone',
                    'billing.street',
                    'billing.city',
                    'billing.state',
                    'billing.zip',
                ])),
            ]
        );

        \DB::beginTransaction();

        $registration = new Registration($organization, $user, $request->input('num_tickets'));

        $registration->createOrder($request->orderData(), function ($order) use ($occurrence, $request) {
            $order
                ->addTickets($request->ticketsData(), ['price' => $occurrence->ticketPrice($request->input('num_tickets'), $request->input('code'))])
                ->addDonation($request->fundAmount());
        });

        try {
            $registration
                ->payDeposit($request->input('payment_type') == 'deposit')
                ->complete($this->paymentGateway, $request->input('stripeToken'));
        } catch (PaymentFailedException $e) {
            \DB::rollback();
            Log::debug($e);

            return redirect()->route('register.create', ['event' => $request->input('event')])->withInput()->with(['error' => $e->getMessage()]);
        }

        \DB::commit();

        SendConfirmationEmail::dispatch($registration->order());

        foreach ($registration->order()->tickets()->with('order.user.person')->get() as $ticket) {
            Mail::to($ticket->order->user->person->email ?? 'matt.floyd@268generation.com')
                ->queue(new WaiverRequest($ticket));
        }

        return redirect()
            ->route('register.confirmation', ['event' => $request->input('event')])
            ->with(['order_id' => $registration->order()->id]);
    }

    public function confirmation()
    {
        $occurrence = new Occurrence(config('occurrences.' . request()->input('event')));

        if (! session()->has('order_id')) {
            return redirect()->route('register.create', ['event' => request()->input('event')]);
        }

        $order = Order::findOrFail(session('order_id'));

        return view('register.confirmation', ['occurrence' => $occurrence])->withOrder($order);
    }
}
