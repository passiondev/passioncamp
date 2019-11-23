<?php

namespace App\Jobs\Order;

use App\Order;
use Illuminate\Bus\Queueable;
use Spatie\Newsletter\Newsletter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddToMailChimp implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(Newsletter $newsletter)
    {
        $newsletter->subscribeOrUpdate($this->order->user->person->email, [
            'FNAME' => $this->order->user->person->first_name,
            'LNAME' => $this->order->user->person->last_name,
        ], 'subscribers', [
            'interests' => [
                'bf4ec83d82' => true,
            ],
        ]);
    }
}
