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
            if ($this->transaction->cc_brand) {
                $type = $this->transaction->cc_brand;
                $method = $this->transaction->cc_last4;
            } else {
                $type = 'Credit Card';
            }
        }

        if ($this->amount < 0) {
            $type = 'Refund';
        }

        if (in_array($this->transaction->source, ['other'])) {
            $type = $this->transaction->identifier;
        }

        return trim(sprintf('%s %s', $type ?? '', $method ?? ''));
    }
}
