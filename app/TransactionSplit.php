<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSplit extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_split';

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
        $type = '';
        $method = $this->transaction->type;

        if ($this->amount < 0) {
            $type = 'Refunded';
        }

        if ($method == 'Sale' || $this->transaction->source == 'stripe') {
            $method = $this->transaction->card_type . ' ' . $this->transaction->card_num;
        }

        if (in_array($method, array('Check', 'Credit'))) {
            $method = $method . ' ' . $this->transaction->processor_transactionid;
        }

        return trim(sprintf('%s %s', $type, $method));
    }
}
