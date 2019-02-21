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

    public function getCcLast4Attribute($cc_last4)
    {
        return substr($cc_last4, -4);
    }

    public function setCcLast4Attribute($cc_last4)
    {
        $this->attributes['cc_last4'] = substr($cc_last4, -4);
    }
}
