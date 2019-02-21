<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Occurrence;
use App\Organization;
use App\Mail\WaiverRequest;
use App\Billing\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Billing\PaymentFailedException;
use App\Jobs\Order\SendConfirmationEmail;
use App\Http\Requests\RegisterCreateRequest;

class RegisterController extends Controller
{
    protected $organization;
    protected $occurrence;
    protected $can_pay_deposit;
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->organization = $this->getOrganization();
        $this->occurrence = new Occurrence('pcc');
        $this->can_pay_deposit = now()->lte(Carbon::parse('2018-05-03')->endOfDay());
        $this->paymentGateway = $paymentGateway;
    }

    private function getOrganization() : Organization
    {
        return Organization::where(['slug' => 'pcc'])->firstOrFail();
    }

    public function create()
    {
        if ($this->occurrence->isClosed()) {
            return view('register.closed', ['occurrence' => $this->occurrence]);
        }

        return view('register.create', [
            'occurrence' => $this->occurrence,
            'ticketPrice' => $this->occurrence->ticketPrice() / 100,
            'can_pay_deposit' => $this->can_pay_deposit,
            'lowestTicketPrice' => $this->occurrence->lowestTicketPrice() / 100,
        ]);
    }

    public function store(RegisterCreateRequest $request)
    {
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

        DB::beginTransaction();

        $registration = $this->organization
            ->newRegistrationForUser($user)
            ->setNumTickets($request->input('num_tickets'));

        $registration->createOrder($request->orderData(), function ($order) use ($request) {
            $order
                ->addTickets($request->ticketsData(), ['price' => $this->occurrence->ticketPrice($request->input('num_tickets'), $request->input('code'))])
                ->addDonation($request->fundAmount());
        });

        try {
            $registration
                ->payDeposit($request->input('payment_type') == 'deposit')
                ->complete($this->paymentGateway, $request->input('stripeToken'));
        } catch (PaymentFailedException $e) {
            DB::rollback();
            Log::debug($e);

            return redirect()
                ->route('register.create')
                ->withInput()
                ->with(['error' => $e->getMessage()]);
        }

        DB::commit();

        SendConfirmationEmail::dispatch($registration->order());

        foreach ($registration->order()->tickets()->with('order.user.person')->get() as $ticket) {
            Mail::to($ticket->order->user->person->email ?? 'matt.floyd@268generation.com')
                ->queue(new WaiverRequest($ticket));
        }

        return redirect()
            ->route('register.confirmation')
            ->with(['order_id' => $registration->order()->id]);
    }

    public function confirmation()
    {
        if (! session()->has('order_id')) {
            return redirect()->route('register.create');
        }

        $order = Order::findOrFail(session('order_id'));

        return view('register.confirmation', ['occurrence' => $this->occurrence])
            ->withOrder($order);
    }
}
