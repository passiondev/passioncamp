<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function getCardNumAttribute()
    {
        if (strlen($this->attributes['cc_last4']) > 4) {
            $this->attributes['cc_last4'] = substr($this->attributes['cc_last4'], -4);
        }

        return $this->attributes['cc_last4'];
    }
}
