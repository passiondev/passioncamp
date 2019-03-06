<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Occurrence;
use App\Organization;
use Illuminate\Support\Carbon;
use App\Billing\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Billing\PaymentFailedException;
use App\Jobs\Order\SendConfirmationEmail;
use App\Http\Requests\RegisterCreateRequest;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    protected $organization;
    protected $occurrence;
    protected $can_pay_deposit;
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->organization = $this->getOrganization();
        $this->occurrence = new Occurrence(config('occurrences.pcc'));
        $this->can_pay_deposit = now()->lte(Carbon::parse('2019-05-03')->endOfDay());
        $this->paymentGateway = $paymentGateway;
    }

    private function getOrganization() : Organization
    {
        return Organization::where(['slug' => 'pcc'])->firstOrFail();
    }

    public function create()
    {
        if ($this->occurrence->isClosed() && request()->get('code') != 'passion2019') {
            return view('register.closed', ['occurrence' => $this->occurrence]);
        }

        return view('register.create', [
            'occurrence' => $this->occurrence,
            'ticketPrice' => $this->occurrence->ticketPrice() / 100,
            'can_pay_deposit' => $this->can_pay_deposit,
        ]);
    }

    public function store(RegisterCreateRequest $request)
    {
        $user = User::firstOrCreate(
            [
                'email' => $request->input('email'),
            ],
            [
                'person' => $request->only([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'street',
                    'city',
                    'state',
                    'zip',
                ]),
            ]
        );

        DB::beginTransaction();

        $registration = $this->organization
            ->newRegistrationForUser($user)
            ->setNumTickets($request->input('num_tickets'));

        $registration->createOrder($request->orderData(), function ($order) use ($request) {
            $order->addTickets($request->ticketsData(), [
                'price' => $this->occurrence->ticketPrice($request->input('num_tickets'), $request->input('code')),
            ]);
        });

        try {
            $registration
                ->shouldPayDeposit($request->wantsToPayDeposit())
                ->complete($this->paymentGateway, $request->input('stripeToken'));
        } catch (PaymentFailedException $e) {
            DB::rollback();
            Log::debug($e->getMessage());

            throw ValidationException::withMessages([
                'payment' => $e->getMessage(),
            ]);
        }

        DB::commit();

        SendConfirmationEmail::dispatch($registration->order());

        $request->session()->flash('order_id', $registration->order()->id);

        return $request->expectsJson()
            ? response()->json([
                'location' => route('register.confirmation'),
            ])
            : redirect()->route('register.confirmation');
    }

    public function confirmation()
    {
        if (! session()->has('order_id')) {
            return redirect()->route('register.create');
        }

        $order = Order::findOrFail(session('order_id'));

        return view('register.confirmation', ['occurrence' => $this->occurrence, 'order' => $order]);
    }
}
