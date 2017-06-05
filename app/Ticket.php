<?php

namespace App;

use Auth;
use App\Waiver;
use Laravel\Scout\Searchable;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\Waiver\RequestWaiverSignature;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\SoftDeletes;
use Facades\App\Contracts\Printing\Factory as Printer;

class Ticket extends OrderItem
{
    use FormAccessible, SoftDeletes, Searchable, Revisionable;

    protected $table = 'order_items';

    protected $type = 'ticket';

    protected $guarded = [];

    protected $attributes = [
        'agegroup' => 'student',
    ];

    protected $casts = [
        'ticket_data' => 'collection'
    ];

    protected $dates = [
        'checked_in_at'
    ];

    protected static $logAttributes = [
        'first_name',
        'last_name',
        'roomId'
    ];

    protected $appends = [
        'name',
        'first_name',
        'last_name',
        'roomId',
    ];

    protected $with = [
        'roomAssignment',
    ];

    protected $observables = [
        'canceled',
    ];

    protected static function boot()
    {
        parent::boot();

        static::observe(TicketObserver::class);

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', '=', 'ticket');
        });
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?? Auth::user();

        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isChurchAdmin()) {
            return $query->whereHas('order.organization', function ($q) use ($user) {
                $q->where('id', $user->organization_id);
            });
        }

        return $query->where('user_id', $user->id);
    }

    public function scopeUnassigned($query)
    {
        return $query->doesntHave('rooms');
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function waiver()
    {
        return $this->hasOne(Waiver::class)->latest();
    }

    public function completedWaivers()
    {
        return $this->waivers()->complete();
    }

    public function waivers()
    {
        return $this->hasMany(Waiver::class);
    }

    public function roomAssignment()
    {
        return $this->hasOne(RoomAssignment::class)->latest();
    }

    public function roomAssignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_assignments')->withTimestamps();
    }

    /*-------------- getters -----------------*/
    public function getNameAttribute()
    {
        return $this->person && strlen($this->person->first_name)
               ? $this->person->name
               : "Ticket #{$this->id}";
    }

    public function getFirstNameAttribute()
    {
        if ($this->person && strlen($this->person->first_name)) {
            return $this->person->first_name;
        }
    }

    public function getLastNameAttribute()
    {
        if ($this->person && strlen($this->person->last_name)) {
            return $this->person->last_name;
        }
    }

    public function getRoomIdAttribute()
    {
        return $this->roomAssignment->room_id ?? null;
    }

    public function getAttribute($key)
    {
        $attribute = parent::getAttribute($key);

        if (is_null($attribute) && $this->exists && isset($this->ticket_data)) {
            $attribute = $this->ticket_data->get($key);
        }

        if (is_null($attribute) && $this->exists && $this->relationLoaded('person')) {
            $attribute = $this->person->$key;
        }

        return $attribute;
    }

    public function getIsCheckedInAttribute()
    {
        return (bool) $this->checked_in_at;
    }

    public function setIsCheckedInAttribute($is_checked_in)
    {
        $this->checked_in_at = $is_checked_in ? \Carbon\Carbon::now() : null;
    }

    public function checkin()
    {
        $this->update(['is_checked_in' => true]);
    }

    public function uncheckin()
    {
        $this->update(['is_checked_in' => false]);
    }

    public function cancel(User $user = null)
    {
        $this->update([
            'canceled_at' => $this->freshTimestamp(),
            'canceled_by_id' => $user->id ?? null
        ]);

        $this->fireModelEvent('canceled', false);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->person->name,
            'organization_id' => $this->order->organization_id,
        ];
    }

    public function createWaiver($provider = 'adobesign')
    {
        if ($this->waivers()->count()) {
            return $this->waivers()->latest();
        }

        $waiver = $this->waiver()->create([
            'provider' => $provider,
        ]);

        dispatch(new RequestWaiverSignature($waiver));

        return $waiver;
    }


    public function toRouteSignatureArray()
    {
        $payload = [
            'id' => $this->id,
        ];

        return [
            'payload' => base64_encode(json_encode($payload)),
            'signature' => RoutePayloadSignature::create($payload),
        ];
    }

    public function printWristband($printer, $driver = null)
    {
        Printer::driver($driver)->print(
            $printer,
            action('TicketWristbandsController@signedShow', $this->toRouteSignatureArray()),
            [
                'title' => $this->name,
                'source' => 'PCC Check In'
            ]
        );
    }
}
