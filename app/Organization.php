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

    protected $with = [
        // 'settings',
    ];

    protected $alias = [
        'total_cost' => 'cost_sum',
        'total_paid' => 'paid_sum',
    ];

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

    public function scopeOrderByChurchName($query)
    {
        $query->orderBySub(
            Church::select('name')->whereRaw('church_id = churches.id')
        );
    }

    public function scopeSearchByChurchName($query, $name)
    {
        $query->whereHas('church', function ($q) use ($name) {
            $q->where('name', 'LIKE', $name . '%');
        });
    }

    public function scopeWithTicketsSum($query)
    {
        return $query->selectSub(function ($q) {
            $q->selectRaw('SUM(quantity)')
                ->from('order_items')
                ->where('org_type', 'ticket')
                ->whereRaw('order_items.owner_id = organizations.id');
        }, 'tickets_sum');
    }

    public function getTicketsSumAttribute($tickets_sum)
    {
        if (! array_key_exists('tickets_sum', $this->attributes)) {
            $tickets_sum = static::newQueryWithoutScopes()
                ->scopes(['withTicketsSum'])
                ->find($this->id)
                ->tickets_sum;

            $this->setAttribute('tickets_sum', $tickets_sum);
        }

        return $tickets_sum;
    }

    public function scopeWithHotelsSum($query)
    {
        return $query->selectSub(function ($q) {
            $q->selectRaw('SUM(quantity)')
                ->from('order_items')
                ->where('org_type', 'hotel')
                ->whereRaw('order_items.owner_id = organizations.id');
        }, 'hotels_sum');
    }

    public function scopeWithCostSum($query)
    {
        return $query->selectSub(function ($q) {
            $q->selectRaw('SUM(quantity * cost)')
                ->from('order_items')
                ->whereRaw('order_items.owner_id = organizations.id');
        }, 'cost_sum');
    }

    public function getCostSumAttribute($cost_sum)
    {
        if (! array_key_exists('cost_sum', $this->attributes)) {
            $cost_sum = static::newQueryWithoutScopes()
                ->scopes(['withCostSum'])
                ->find($this->id)
                ->cost_sum;

            $this->setAttribute('cost_sum', $cost_sum);
        }

        return $cost_sum;
    }

    public function scopeWithPaidSum($query, $source = null)
    {
        return $query->selectSub(function ($q) use ($source) {
            $q->selectRaw('SUM(transaction_splits.amount)')
                ->from('transaction_splits')
                ->when($source, function ($q) use ($source) {
                    $q->join('transactions', 'transaction_id', 'transactions.id')
                        ->where('source', $source);
                })
                ->whereRaw('transaction_splits.organization_id = organizations.id');
        }, $source ? $source . '_paid_sum' : 'paid_sum');
    }

    public function getPaidSumAttribute($paid_sum)
    {
        if (! array_key_exists('paid_sum', $this->attributes)) {
            $paid_sum = static::newQueryWithoutScopes()
                ->scopes(['withPaidSum'])
                ->find($this->id)
                ->paid_sum;

            $this->setAttribute('paid_sum', $paid_sum);
        }

        return $paid_sum;
    }

    public function church()
    {
        return $this->belongsTo(Church::class)->withDefault();
    }

    public function contact()
    {
        return $this->belongsTo(Person::class)->withDefault();
    }

    public function studentPastor()
    {
        return $this->belongsTo(Person::class)->withDefault();
    }

    public function items()
    {
        return $this->morphMany(OrgItem::class, 'owner');
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
        return $this->morphToMany(Hotel::class, 'owner', 'order_items', 'owner_id', 'item_id');
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
        return $this->hasManyThrough(Ticket::class, Order::class, 'organization_id', 'owner_id');
    }

    public function students()
    {
        return $this->attendees()->where('agegroup', 'student')->active();
    }

    public function checkedInStudents()
    {
        return $this->students()->whereNotNull('checked_in_at');
    }

    public function leaders()
    {
        return $this->attendees()->where('agegroup', 'leader')->active();
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

    public function getDepositBalanceAttribute()
    {
        return 0;
    }

    public function getBalanceAttribute()
    {
        return $this->cost_sum - $this->paid_sum;
    }

    public function getCanReceivePaymentAttribute()
    {
        return $this->slug == 'pcc';
    }

    public function getTicketCountAttribute()
    {
        return $this->tickets->sum('quantity');
    }

    public function getActiveAttendeesCountAttribute($active_attendees_count)
    {
        if (! array_key_exists('active_attendees_count', $this->attributes)) {
            $active_attendees_count = $this->activeAttendees()->count();
        }

        return $active_attendees_count;
    }

    public function getTicketsRemainingCountAttribute()
    {
        return $this->tickets_sum - $this->active_attendees_count;
    }

    public function canAddTickets()
    {
        if ($this->slug == 'pcc') {
            return true;
        }

        return $this->tickets_remaining_count > 0;
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
