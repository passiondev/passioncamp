<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSplit extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $touches = ['organization', 'order'];

    protected $with = ['transaction'];

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
        if ($this->amount < 0) {
            return 'Refund';
        }

        if (in_array($this->transaction->source, ['other'])) {
            return $this->transaction->identifier;
        }

        if ($this->transaction->cc_brand) {
            return $this->transaction->cc_brand . ' ' . $this->transaction->cc_last4;
        }

        return 'Credit Card';
    }
}
