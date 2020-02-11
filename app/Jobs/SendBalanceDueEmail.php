<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mandrill;

class SendBalanceDueEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $user;
    private $template = 'pcc-students-smmr-cmp-2017';

    /**
     * Create a new job instance.
     *
     * @param mixed $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(Mandrill $mandrill)
    {
        $message = [
            'subject' => 'SMMR CMP Reminder',
            'to' => [
                [
                    'name' => $this->user->person->name,
                    'email' => $this->user->person->email,
                ],
            ],
            'global_merge_vars' => [
                [
                    'name' => 'BODY',
                    'content' => view('emails.user.balance-due', ['user' => $this->user])->render(),
                ],
            ],
        ];

        $mandrill->messages->sendTemplate($this->template, null, $message);
    }
}
