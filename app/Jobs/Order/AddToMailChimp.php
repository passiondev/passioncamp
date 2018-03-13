<?php

namespace App\Jobs\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Order;
use Spatie\Newsletter\Newsletter;

class AddToMailChimp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Newsletter $newsletter)
    {
        $newsletter->subscribeOrUpdate($this->order->user->person->email, [
            'FNAME' => $this->order->user->person->first_name,
            'LNAME' => $this->order->user->person->last_name,
        ], 'subscribers', [
            'interests' => [
                'bf4ec83d82' => true
            ]
        ]);
    }
}
