<?php

namespace App;

use App\Collections\OrganizationCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes, Notated, Cacheable;

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
        return $query->addSubSelect(
            'tickets_sum',
            OrgItem::withoutTrashed()
                ->selectRaw('SUM(quantity)')
                ->where('org_type', 'ticket')
                ->whereRaw('order_items.owner_id = organizations.id')
        );
    }

    public function getTicketsSumAttribute($tickets_sum)
    {
        if (array_key_exists('tickets_sum', $this->attributes)) {
            return $tickets_sum;
        }

        return tap($this->tickets()->sum('quantity'), function ($tickets_sum) {
            $this->setAttribute('tickets_sum', $tickets_sum);
        });
    }

    public function getCachedTicketsSumAttribute()
    {
        return $this->cached('tickets_sum');
    }

    public function scopeWithHotelsSum($query)
    {
        return $query->addSubSelect(
            'hotels_sum',
            OrgItem::withoutTrashed()->selectRaw('SUM(quantity)')->where('org_type', 'hotel')->whereRaw('order_items.owner_id = organizations.id')
        );
    }

    public function getHotelsSumAttribute($hotels_sum)
    {
        if (array_key_exists('hotels_sum', $this->attributes)) {
            return $hotels_sum;
        }

        return tap($this->hotelItems()->sum('quantity'), function ($hotels_sum) {
            $this->setAttribute('hotels_sum', $hotels_sum);
        });
    }

    public function getCachedHotelsSumAttribute()
    {
        return $this->cached('hotels_sum');
    }

    public function scopeWithCostSum($query)
    {
        return $query->addSubSelect(
            'cost_sum',
            OrgItem::withoutTrashed()->selectRaw('SUM(quantity * cost)')->whereRaw('order_items.owner_id = organizations.id')
        );
    }

    public function getCostSumAttribute($cost_sum)
    {
        if (array_key_exists('cost_sum', $this->attributes)) {
            return $cost_sum;
        }

        return tap($this->items()->selectRaw('SUM(quantity * cost) as cost_sum')->first()->cost_sum, function ($cost_sum) {
            $this->setAttribute('cost_sum', $cost_sum);
        });
    }

    public function getCachedCostSumAttribute()
    {
        return $this->cached('cost_sum');
    }

    public function scopeWithPaidSum($query, $source = null)
    {
        return $query->addSubSelect(
            $source ? $source . '_paid_sum' : 'paid_sum',
            TransactionSplit::withoutTrashed()
                ->selectRaw('SUM(transaction_splits.amount)')
                ->when($source, function ($q) use ($source) {
                    $q->join('transactions', 'transaction_id', 'transactions.id')
                        ->where('source', $source);
                })
                ->whereRaw('transaction_splits.organization_id = organizations.id')
        );
    }

    public function getPaidSumAttribute($paid_sum)
    {
        if (array_key_exists('paid_sum', $this->attributes)) {
            return $paid_sum;
        }

        return tap($this->transactions()->sum('amount'), function ($paid_sum) {
            $this->setAttribute('paid_sum', $paid_sum);
        });
    }

    public function getCachedPaidSumAttribute()
    {
        return $this->cached('paid_sum');
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

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class, 'organization_id', 'owner_id');
    }

    public function donations()
    {
        return $this->orderItems()->where('type', 'donation');
    }

    public function orderTransactions()
    {
        return $this->hasManyThrough(TransactionSplit::class, Order::class, 'organization_id', 'order_id');
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

    public function getAssignedToRoomCountAttribute($assigned_to_room_count)
    {
        if (array_key_exists('assigned_to_room_count', $this->attributes)) {
            return $assigned_to_room_count;
        }

        return tap($this->assignedToRoom()->count(), function ($assigned_to_room_count) {
            $this->setAttribute('assigned_to_room_count', $assigned_to_room_count);
        });
    }

    public function getCachedAssignedToRoomCountAttribute()
    {
        return $this->cached('assigned_to_room_count');
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

    public function getRoomsCountAttribute($rooms_count)
    {
        if (array_key_exists('rooms_count', $this->attributes)) {
            return $rooms_count;
        }

        return tap($this->rooms()->count(), function ($rooms_count) {
            $this->setAttribute('rooms_count', $rooms_count);
        });
    }

    public function getCachedRoomsCountAttribute()
    {
        return $this->cached('rooms_count');
    }

    public function checkedInRooms()
    {
        return $this->rooms()->whereNotNull('checked_in_at');
    }

    public function getCheckedInRoomsCountAttribute($checked_in_rooms_count)
    {
        if (array_key_exists('checked_in_rooms_count', $this->attributes)) {
            return $checked_in_rooms_count;
        }

        return tap($this->checkedInRooms()->count(), function ($checked_in_rooms_count) {
            $this->setAttribute('checked_in_rooms_count', $checked_in_rooms_count);
        });
    }

    public function getCachedCheckedInRoomsCountAttribute()
    {
        return $this->cached('checked_in_rooms_count');
    }

    public function keyReceivedRooms()
    {
        return $this->rooms()->whereNotNull('key_received_at');
    }

    public function getKeyReceivedRoomsCountAttribute($key_received_rooms_count)
    {
        if (array_key_exists('key_received_rooms_count', $this->attributes)) {
            return $key_received_rooms_count;
        }

        return tap($this->keyReceivedRooms()->count(), function ($key_received_rooms_count) {
            $this->setAttribute('key_received_rooms_count', $key_received_rooms_count);
        });
    }

    public function getCachedKeyReceivedRoomsCountAttribute()
    {
        return $this->cached('key_received_rooms_count');
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

    public function getBalanceAttribute($balance)
    {
        if (array_key_exists('balance', $this->attributes)) {
            return $balance;
        }

        return tap($this->cost_sum - $this->paid_sum, function ($balance) {
            $this->setAttribute('balance', $balance);
        });
    }

    public function getCachedBalanceAttribute()
    {
        return $this->cached('balance');
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
        if (array_key_exists('active_attendees_count', $this->attributes)) {
            return $active_attendees_count;
        }

        return tap($this->activeAttendees()->count(), function ($active_attendees_count) {
            $this->setAttribute('active_attendees_count', $active_attendees_count);
        });
    }

    public function getCachedActiveAttendeesCountAttribute()
    {
        return $this->cached('active_attendees_count');
    }

    public function getTicketsRemainingCountAttribute()
    {
        return $this->tickets_sum - $this->active_attendees_count;
    }

    public function canAddTickets()
    {
        return $this->tickets_remaining_count > 0;
    }

    public function getCanMakeStripePaymentsAttribute()
    {
        return $this->slug == 'pcc';
    }

    public function getCanRecordTransactionsAttribute()
    {
        return $this->slug == 'pcc';
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

    public function getCompletedWaiversCountAttribute($completed_waivers_count)
    {
        if (array_key_exists('completed_waivers_count', $this->attributes)) {
            return $completed_waivers_count;
        }

        return tap($this->completedWaivers()->count(), function ($completed_waivers_count) {
            $this->setAttribute('completed_waivers_count', $completed_waivers_count);
        });
    }

    public function getCachedCompletedWaiversCountAttribute()
    {
        return $this->cached('completed_waivers_count');
    }

    public function getIsCheckedInAttribute($is_checked_in)
    {
        if (array_key_exists('is_checked_in', $this->attributes)) {
            return $is_checked_in;
        }

        return (bool) $this->cached_checked_in_rooms_count || (bool) $this->setting('checked_in');
    }

    public function getActiveAttendeesTotalAttribute()
    {
        static $attendees_price_sum;

        if (is_null($attendees_price_sum)) {
            $attendees_price_sum = $this->activeAttendees()->sum('price');
        }

        return $attendees_price_sum;
    }

    public function getStudentsCountAttribute($students_count)
    {
        if (array_key_exists('students_count', $this->attributes)) {
            return $students_count;
        }

        static $students_count = null;

        if (is_null($students_count)) {
            $students_count = $this->students()->count();
        }

        return $students_count;
    }

    public function getLeadersCountAttribute($leaders_count)
    {
        if (array_key_exists('leaders_count', $this->attributes)) {
            return $leaders_count;
        }

        static $leaders_count = null;

        if (is_null($leaders_count)) {
            $leaders_count = $this->leaders()->count();
        }

        return $leaders_count;
    }

    public function getDonationsTotalAttribute($donations_total)
    {
        static $donations_total = null;

        if (is_null($donations_total)) {
            $donations_total = $this->donations()->sum('price');
        }

        return $donations_total;
    }

    public function getOrdersGrandTotalAttribute($orders_grand_total)
    {
        static $orders_grand_total = null;

        if (is_null($orders_grand_total)) {
            $orders_grand_total = $this->orderItems()->active()->sum('price');
        }

        return $orders_grand_total;
    }

    public function getOrdersTransactionsTotalAttribute($orders_grand_total)
    {
        static $orders_grand_total = null;

        if (is_null($orders_grand_total)) {
            $orders_grand_total = $this->orderTransactions()->sum('amount');
        }

        return $orders_grand_total;
    }

    public function registration()
    {
        return new Registration($this);
    }

    public function newRegistrationForUser($user)
    {
        return new Registration($this, $user);
    }
}
