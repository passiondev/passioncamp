<?php

namespace App\Jobs\Order;

use Mandrill;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Order;

class SendConfirmationEmail implements ShouldQueue
{
    private $order;
    private $template = 'pcc-students-passion-camp-2018';

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mandrill $mandrill)
    {
        $message = [
            'subject' => 'Passion Camp Registration Confirmation',
            'to' => [
                [
                    'name' => $this->order->user->person->name,
                    'email' => $this->order->user->person->email,
                ]
            ],
            'global_merge_vars' => [
                [
                    'name' => 'BODY',
                    'content' => view('emails.order.confirmation', ['order' => $this->order])->render()
                ],
            ]
        ];

        $mandrill->messages->sendTemplate($this->template, null, $message);
    }
}
