<?php

namespace App\Auth\Traits;

use App\EmailLogin;
use App\Mail\MagicLinkRequested;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait HasEmailLogin
{
    public function emailLogin()
    {
        return $this->hasOne(EmailLogin::class);
    }

    public function createMagicToken()
    {
        $this->deleteExistingMagicToken();

        $this->emailLogin()->create([
            'token' => Str::random(32),
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
        return null === $this->access;
    }

    protected function deleteExistingMagicToken()
    {
        $this->emailLogin()->delete();
    }
}
