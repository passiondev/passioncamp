<?php

namespace App\Listeners\User;

use App\Events\UserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegistrationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        Mail::queue('auth.emails.pcc', compact('user'), function ($m) use ($user) {
            $m->from('students@passioncitychurch.com', 'PCC Students');
            $m->subject('Create Your Account');
            $m->to($user->email, $user->person->name);
        });
    }
}
