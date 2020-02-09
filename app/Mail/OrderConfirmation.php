<?php

namespace App\Mail;

use App\Occurrence;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Passion Students Registration Confirmation')
            ->markdown('emails.order.confirmation')
            ->with([
                'occurrence' => new Occurrence(config('occurrences.'.$this->order->organization->slug)),
                'order' => $this->order,
            ]);
    }
}
