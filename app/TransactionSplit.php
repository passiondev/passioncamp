<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionSplit extends Model
{
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
}
