<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use App\Collections\OrganizationCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes, Notated;

    protected $casts = [
        'tickets_sum' => 'integer',
        'hotels_sum' => 'integer',
    ];

    protected static function boot()
    {
        static::addGlobalScope('activeAttendeesCount', function ($builder) {
            $builder->withCount('activeAttendees');
        });

        static::addGlobalScope('ticketsSum', function ($builder) {
            $builder->withTicketsSum();
        });
    }

    public function newCollection(array $models = [])
    {
        return new OrganizationCollection($models);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('items', function ($q) {
            $q->whereIn('org_type', ['ticket', 'hotel'])->where('quantity', '>', '0');
        });
    }

    public function scopeWithTicketsSum($query)
    {
        return $query->selectSub("
                SELECT SUM(quantity)
                FROM order_items
                WHERE order_items.organization_id = organizations.id and org_type = 'ticket'
            ", 'tickets_sum');
    }

    public function scopeWithHotelsSum($query)
    {
        return $query->selectSub("
                SELECT SUM(quantity)
                FROM order_items
                WHERE order_items.organization_id = organizations.id and org_type = 'hotel'
            ", 'hotels_sum');
    }

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
        return $this->hasMany(OrderItem::class)->whereNotNull('org_type');
    }

    public function hotelItems()
    {
        return $this->items()->where('org_type', 'hotel');
    }

    public function getHotelItemsCountAttribute()
    {
        return $this->hotelItems->sum('quantity');
    }

    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'order_items', 'organization_id', 'item_id');
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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function attendees()
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Ticket::class, Order::class)->where('agegroup', 'student')->active();
    }

    public function checkedInStudents()
    {
        return $this->students()->whereNotNull('checked_in_at');
    }

    public function leaders()
    {
        return $this->hasManyThrough(Ticket::class, Order::class)->where('agegroup', 'leader')->active();
    }

    public function checkedInLeaders()
    {
        return $this->leaders()->whereNotNull('checked_in_at');
    }

    public function activeAttendees()
    {
        return $this->attendees()->active();
    }

    public function assignedToRoom()
    {
        return $this->activeAttendees()->has('rooms');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function settings()
    {
        return $this->hasMany(OrganizationSettings::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function checkedInRooms()
    {
        return $this->rooms()->whereNotNull('checked_in_at');
    }

    public function keyReceivedRooms()
    {
        return $this->rooms()->whereNotNull('key_received_at');
    }

    public function getRoomsNeededAttribute()
    {
        return $this->hotel_items_count - $this->rooms->count();
    }

    public function setting($key, $value = null)
    {
        if (! is_null($value)) {
            $key = [$key => $value];
        }

        if (is_array($key)) {
            collect($key)->each(function ($value, $key) {
                return $this->addSetting($key, $value);
            });

            return $this;
        }

        $setting = $this->settings->where('key', $key)->first();

        return $setting ? $setting->value : false;
    }

    protected function addSetting($key, $value)
    {
        $setting = $this->settings()->where('key', $key)->first();

        if (! $setting) {
            $setting = new OrganizationSettings;
            $setting->key = $key;
            $setting->organization()->associate($this);
        }

        $setting->value = $value;

        $setting->save();
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

    public function getTicketCountAttribute()
    {
        return $this->tickets->sum('quantity');
    }

    public function getTicketsRemainingCountAttribute()
    {
        return $this->tickets_sum - $this->active_attendees_count;
    }

    public function getCanMakeStripePaymentsAttribute()
    {
        return (bool) $this->setting('stripe_user_id');
    }

    public function getCanRecordTransactionsAttribute()
    {
        return (bool) $this->setting('use_transactions');
    }

    public function roomCountForHotel($hotel)
    {
        $items = $this->hotelItems()->where('item_id', $hotel->id);

        return number_format($items->sum('quantity'));
    }

    public function getSignedWaiversCountAttribute()
    {
        return $this->attendees->active()->filter(function ($attendee) {
            return $attendee->waiver && $attendee->waiver->status == 'signed';
        })->count();
    }

    public static function totalPaid($source = null)
    {
        if ($source == null) {
            return static::withoutGlobalScopes()->with('transactions')->get()->sum('total_paid');
        }

        return static::withoutGlobalScopes()->with('transactions.transaction')->get()
            ->pluck('transactions')
            ->collapse()
            ->filter(function ($transaction) use ($source) {
                return $transaction->transaction->source == $source;
            })
            ->sum('transaction.amount');
    }

    public static function totalCost()
    {
        return static::withoutGlobalScopes()->with('items')->get()->sum('total_cost');
    }

    public function addTransaction($data = [])
    {
        $transaction = Transaction::create($data);

        $this->transactions()->create([
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
        ]);
    }

    public function completedWaivers()
    {
        return $this->activeAttendees()->has('completedWaivers');
    }

    public function getIsCheckedInAttribute()
    {
        return !! $this->checked_in_rooms_count || !! $this->setting('checked_in');
    }
}
