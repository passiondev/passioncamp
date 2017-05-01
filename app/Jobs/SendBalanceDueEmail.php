<?php

namespace App\Jobs;

use Mandrill;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBalanceDueEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $template = 'pcc-students-smmr-cmp-2017';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mandrill $mandrill)
    {
        $message = [
            'subject' => 'SMMR CMP Reminder',
            'to' => [
                [
                    'name' => $this->user->person->name,
                    'email' => $this->user->person->email,
                ]
            ],
            'global_merge_vars' => [
                [
                    'name' => 'BODY',
                    'content' => view('emails.user.balance-due', ['user' => $this->user])->render()
                ],
            ]
        ];

        $mandrill->messages->sendTemplate($this->template, null, $message);
    }
}
