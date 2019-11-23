<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MagicLinkRequested extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $user;

    protected $options;

    /**
     * Create a new message instance.
     *
     * @param mixed $options
     */
    public function __construct(User $user, $options)
    {
        $this->user = $user;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Magic sign in link for Passion Students')
            ->markdown('emails.auth.magic-link')
            ->with([
                'link' => $this->buildLink(),
            ]);
    }

    public function buildLink()
    {
        return route('magic.authenticate', [
            'token' => $this->user->emailLogin->token,
        ] + $this->options);
    }
}
