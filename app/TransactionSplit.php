<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSplit extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getNameAttribute()
    {
        if ($this->transaction->source == 'stripe') {
            $type = $this->transaction->cc_brand;
            $method = $this->transaction->cc_last4;
        }

        if (in_array($this->transaction->source, ['check', 'credit'])) {
            $type = ucfirst($this->transaction->source);
            $method = $this->transaction->identifier;
        }

        if ($this->amount < 0) {
            $type = 'Refunded';
        }

        return trim(sprintf('%s %s', $type, $method));
    }
}
