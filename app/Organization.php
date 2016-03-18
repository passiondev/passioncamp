<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organization';
    
    public function church()
    {
        return $this->belongsTo(Church::class);
    }

    public function contact()
    {
        return $this->belongsTo(Person::class);
    }

    public function studentPastor()
    {
        return $this->belongsTo(Person::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->items()->where('org_type', 'ticket');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionSplit::class);
    }

    public function authUsers()
    {
        return $this->hasMany(User::class);
    }

    public function attendees()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class)->where('type', 'ticket');
    }

    public function getNumTicketsAttribute()
    {
        $quantity = $this->tickets->sum('quantity');

        return number_format($quantity, 0);
    }

    public function getTotalCostAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->cost * $item->quantity;
        });
    }

    public function getTotalPaidAttribute()
    {
        return $this->transactions->sum('amount');
    }

    public function getDepositBalanceAttribute()
    {
        return 0;
    }

    public function getBalanceAttribute()
    {
        return $this->total_cost - $this->total_paid;
    }

    public function getCanReceivePaymentAttribute()
    {
        return $this->slug == 'pcc';
    }

    public function addPayment($data)
    {
        if ($data->type == 'check') {
            return $this->addCheckPayment($data);
        }

        if ($data->type == 'credit') {
            return $this->addCreditPayment($data);
        }

        throw new \Exception("Could not add payment: payment type not available.");
    }

    private function addCheckPayment($data)
    {
        # code...
    }

    private function addCreditPayment($data)
    {
        # code...
    }
}
