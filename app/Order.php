<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Eloquence;

    protected $table = 'orders';

    protected $searchableColumns = ['id', 'tickets.person.first_name', 'tickets.person.last_name'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payment()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(OrderItem::class)->where('type', 'ticket');
    }

    public function donations()
    {
        return $this->hasMany(OrderItem::class)->where('type', 'donation');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionSplit::class);
    }

    public function getNumTicketsAttribute()
    {
        return $this->tickets->count();
    }

    public function getTicketCountAttribute()
    {
        return $this->tickets->count();
    }

    public function getTicketTotalAttribute()
    {
        return $this->tickets->sum('price');
    }

    public function getDonationTotalAttribute()
    {
        return $this->donations->sum('price');
    }

    public function getGrandTotalAttribute()
    {
        return $this->items->sum('price');
    }

    public function getTransactionsTotalAttribute()
    {
        return $this->transactions->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->grand_total - $this->transactions_total;
    }

    public function addTransaction($data = [])
    {
        $transaction = Transaction::create($data + ['type' => 'Sale']);

        $split = new TransactionSplit;
        $split->transaction_id = $transaction->id;
        $split->amount = $transaction->amount;

        $this->transactions()->save($split);
    }

    public function addContact($data)
    {
        $person = Person::create($data);

        $user = new User;
        $user->person()->associate($person);
        $user->save();

        $this->user()->associate($user);

        return $this;
    }

    public function addTicket($data)
    {
        $person = Person::create(array_only($data, [
            'first_name',
            'last_name',
            'email',
            'phone',
            'birthdate',
            'gender',
            'grade',
            'allergies',
        ]));

        $ticket_data = array_only($data, [
            'school',
            'shirtsize',
            'roommate_requested',
            'location'
        ]);

        $ticket = new Ticket;
        $ticket->order()->associate($this);
        $ticket->person()->associate($person);
        $ticket->organization_id = $this->organization_id;
        $ticket->agegroup = $data['agegroup'];
        $ticket->price = $data['price'];
        $ticket->ticket_data = $ticket_data;
        $ticket->save();
    }
}
