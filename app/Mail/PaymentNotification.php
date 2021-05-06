<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $church_name;

    public $amount;

    public $subject = 'Passion Camp Portal Payment';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($church_name, $amount)
    {
        $this->church_name = $church_name;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order.internal_payment_notification');
    }
}