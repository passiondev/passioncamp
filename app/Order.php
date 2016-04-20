<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use App\Collections\OrderCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use Eloquence, SoftDeletes;

    protected $table = 'orders';

    protected $searchableColumns = ['id', 'tickets.person.first_name', 'tickets.person.last_name'];

    public function newCollection(array $models = [])
    {
        return new OrderCollection($models);
    }

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

    public function activeTickets()
    {
        return $this->tickets()->whereNull('canceled_at');
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
        return $this->ticket_count;
    }

    public function getTicketCountAttribute()
    {
        return $this->tickets->active()->count();
    }

    public function getStudentCountAttribute()
    {
        return $this->getTicketsOfAgegroupCount('student');
    }

    public function getLeaderCountAttribute()
    {
        return $this->getTicketsOfAgegroupCount('leader');
    }

    private function getTicketsOfAgegroupCount($agegroup)
    {
        return $this->tickets->active()->ofAgegroup($agegroup)->count();
    }

    public function getTicketTotalAttribute()
    {
        return $this->tickets->active()->sum('price');
    }

    public function getDonationTotalAttribute()
    {
        return $this->donations->active()->sum('price');
    }

    public function getGrandTotalAttribute()
    {
        return $this->items->active()->sum('price');
    }

    public function getTransactionsTotalAttribute()
    {
        return $this->transaction_total;
    }

    public function getTransactionTotalAttribute()
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
        $ticket->organization()->associate($this->organization);
        $ticket->person()->associate($person);

        $ticket->fill($data);
        $ticket->ticket_data = $ticket_data;

        $ticket->save();
    }
}
