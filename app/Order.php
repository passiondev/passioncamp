<?php

namespace App;

use App\Collections\OrderCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes, Notated;

    protected $guarded = [];

    protected $touches = ['organization'];

    protected $searchableColumns = [
        'id',
        'tickets.person.first_name',
        'tickets.person.last_name',
    ];

    public function newCollection(array $models = [])
    {
        return new OrderCollection($models);
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?? Auth::user();

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isChurchAdmin()) {
            return $query->where('organization_id', $user->organization_id);
        }

        return $query->where('user_id', $user->id);
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
        return $this->morphMany(OrderItem::class, 'owner');
    }

    public function tickets()
    {
        return $this->morphMany(Ticket::class, 'owner');
    }

    public function activeTickets()
    {
        return $this->tickets()->active();
    }

    public function donations()
    {
        return $this->items()->where('type', 'donation');
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

    public function getDepositTotalAttribute()
    {
        return array_sum([
            $this->donation_total,
            $this->ticket_count * 7500,
        ]);
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
        $transaction = Transaction::create($data);

        $this->transactions()->create([
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
        ]);
    }

    public function addContact($data)
    {
        $this->user()->create([
            'person_id' => Person::create($data)->id,
        ]);

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

        dd('todo');
        // $this->ticket_repo->make($data)
        //     ->order()->associate($this)
        //     ->organization()->associate($this->organization)
        //     ->person()->associate($person)
        //     ->save();
    }

    public function hasContactInfo()
    {
        return $this->user && $this->user->person && $this->user->person->email;
    }
}
