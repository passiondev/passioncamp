<?php

namespace App\Repositories;

use App\Transaction;
use App\TransactionSplit;

class TransactionRepository
{
    public function refund(TransactionSplit $transaction, $amount)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $charge = \Stripe\Charge::retrieve(
            $transaction->transaction->processor_transactionid,
            ['stripe_account' => $transaction->order->organization->setting('stripe_user_id')]
        );

        $refund = $charge->refunds->create([
            'amount' => $amount * 100
        ]);

        $this->newStripeTransaction($refund)->order()->associate($transaction->order)->save();
    }

    protected function newStripeTransaction($charge)
    {
        if ($charge->object == 'refund') {
            $charge->amount *= -1;
        }

        $transaction = new Transaction;
        $transaction->forceFill([
            'amount' => $charge->amount / 100,
            'processor_transactionid' => $charge->id,
            'type' => ucwords($charge->object),
            'source' => 'stripe'
        ]);

        if ($charge->object == 'charge') {
            $transaction->forceFill([
                'card_type' => $charge->source->brand,
                'card_num' => $charge->source->last4,
            ]);
        }

        $transaction->save();

        $split = new TransactionSplit;
        $split->transaction()->associate($transaction);
        $split->amount = $charge->amount / 100;

        return $split;
    }
}
