<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transaction';

    protected static $unguarded = true;

    public function getCardNumAttribute()
    {
        if ($this->type == 'Check') {
            return "#".$this->attributes['card_num'];
        }

        if (strlen($this->attributes['card_num']) > 4) {
            $this->attributes['card_num'] = substr($this->attributes['card_num'], -4);
        }

        return $this->attributes['card_num'];
    }
}
