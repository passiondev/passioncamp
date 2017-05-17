<?php

namespace App\Exports\Transformers;

use App\Person;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['contact'];

    public function transform($order)
    {
        $data = [
            'id' => $order->id,
            'created at' => $order->created_at->toDateTimeString(),
            'updated at' => $order->updated_at->toDateTimeString(),
            'tickets' => $order->active_tickets_count,
            'donation' => $order->donation_total / 100,
            'grand total' => $order->grand_total / 100,
            'total paid' => $order->transactions_total / 100,
            'order balance' => $order->balance / 100,
            'account balance' => $order->user ? $order->user->balance / 100 : '',
        ];

        return $data;
    }

    public function includeContact($order)
    {
        return $this->item($order->user->person ?? new Person, new ContactTransformer);
    }
}
