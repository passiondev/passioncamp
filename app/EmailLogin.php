<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailLogin extends Model
{
    const TOKEN_EXPIRY = 1440; // 24 hours in minutes

    protected $guarded = [];

    public function validateRequest(Request $request)
    {
        $this->delete();

        if (!$this->isExpired()) {
            throw new \RuntimeException('This link is expired.');
        }

        if ($this->user->email != $request->input('email')) {
            throw new \RuntimeException('This link is invalid.');
        }

        if (!$this->user->canUseMagicLink()) {
            throw new \RuntimeException('This user is invalid.');
        }

        return $this;
    }

    public function isExpired()
    {
        return $this->created_at->diffInMinutes(Carbon::now()) < static::TOKEN_EXPIRY;
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
