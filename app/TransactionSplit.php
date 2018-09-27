<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSplit extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $touches = ['organization', 'order'];

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

    public function getTransactionAttribute()
    {
        return $this;
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

    public function setCcLast4Attribute($cardnum)
    {
        $this->attributes['cc_last4'] = substr($cardnum, -4);
    }

    public function migrateTransactionData()
    {
        $transaction = $this->getRelation('transaction');

        $this->update([
            'source' => $transaction->source,
            'identifier' => $transaction->identifier,
            'cc_brand' => $transaction->cc_brand,
            'cc_last4' => $transaction->cc_last4,
            'transaction_id' => null,
        ]);
    }
}
