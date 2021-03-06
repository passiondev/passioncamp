<?php

namespace App\Auth\Traits;

use App\EmailLogin;
use App\Mail\MagicLinkRequested;
use Illuminate\Support\Facades\Mail;

trait HasEmailLogin
{
    public function emailLogin()
    {
        return $this->hasOne(EmailLogin::class);
    }

    protected function deleteExistingMagicToken()
    {
        $this->emailLogin()->delete();
    }

    public function createMagicToken()
    {
        $this->deleteExistingMagicToken();

        $this->emailLogin()->create([
            'token' => str_random(32),
        ]);

        return $this;
    }

    public function sendMagicLink(array $options)
    {
        Mail::to($this)->send(new MagicLinkRequested($this, $options));

        return $this;
    }

    public function canUseMagicLink()
    {
        return $this->access === null;
    }
}
