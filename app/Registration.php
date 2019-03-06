<?php

namespace App;

use Illuminate\Support\Carbon;

class Registration
{
    protected $organization;

    protected $user;

    protected $order;

    protected $payInFull = true;

    public static $closedDate = '2019-06-03';

    public static $depositCutoffDate = '2019-05-03';

    protected $numTickets;

    public function __construct($organization = null, $user = null, $numTickets = 1)
    {
        $this->organization = $organization;
        $this->user = $user;
        $this->numTickets = $numTickets;
    }

    public function setNumTickets($numTickets)
    {
        $this->numTickets = $numTickets;

        return $this;
    }

    public function createOrder($orderData, $callback)
    {
        $order = $this->user->orders()->create([
            'organization_id' => $this->organization->id,
            'order_data' => $orderData,
        ]);

        $callback($order);

        $this->order = $order;
    }

    public function order()
    {
        return $this->order;
    }

    public function complete($paymentGateway, $paymentToken)
    {
        $amount = $this->payInFull
            ? $this->order->grand_total
            : $this->order->deposit_total;

        $charge = $paymentGateway->charge($amount, $paymentToken, [
            'description' => (new Occurrence(config('occurrences.' . $this->organization->slug)))->title,
            'statement_descriptor' => 'Passion Students',
            'metadata' => [
                'order_id' => $this->order->id,
                'email' => $this->user->person->email,
                'name' => $this->user->person->name,
            ],
        ]);

        $this->order->addTransaction([
            'source' => $charge->source(),
            'identifier' => $charge->identifier(),
            'amount' => $charge->amount(),
            'cc_brand' => $charge->cardBrand(),
            'cc_last4' => $charge->cardLastFour(),
        ]);
    }

    public function shouldPayDeposit($payDeposit = true)
    {
        if (! $this->canPayDeposit()) {
            return $this;
        }

        $this->payInFull = ! $payDeposit;

        return $this;
    }

    public function canPayDeposit()
    {
        return now()->lte(Carbon::parse(static::$depositCutoffDate)->endOfDay());
    }

    public function isClosed()
    {
        return now()->gte(Carbon::parse(static::$closedDate)->endOfDay());
    }
}
