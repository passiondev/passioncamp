<?php

namespace App;

use Auth;
use App\Waiver;
use Illuminate\Database\Eloquent\Builder;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\Revisionable;

class Ticket extends OrderItem
{
    use FormAccessible, SoftDeletes;
    // use Revisionable;

    protected $revisionPresenter = \App\Presenters\Revisions\Ticket::class;

    public function isRevisioned()
    {
        return false;
    }

    protected $appends = ['name'];

    protected $revisionable = ['name', 'room_id'];

    protected $table = 'order_items';

    protected $type = 'ticket';

    protected $searchableColumns = ['id', 'person.first_name', 'person.last_name'];

    protected $guarded = [];

    protected $dates = ['checked_in_at'];

    protected $casts = [
        'ticket_data' => 'collection'
    ];

    protected $attributes = [
        'agegroup' => 'student',
    ];

    protected static function boot()
    {
        parent::boot();

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
        return $query->whereNull('room_id');
    }

    public function waiver()
    {
        return $this->hasOne(Waiver::class)->active();
    }

    public function waivers()
    {
        return $this->hasMany(Waiver::class)->active();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /*-------------- getters -----------------*/
    public function getNameAttribute()
    {
        return $this->person && strlen($this->person->first_name)
               ? $this->person->name
               : "Ticket #{$this->id}";
    }

    public function getFullNameAttribute()
    {
        return 'full name!';
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

    /*-------------- setters -----------------*/

    public function getShirtsizeAttribute()
    {
        $shirtsize = $this->ticket_data('shirtsize');
        $sizes = ['XS','S','M','L','XL'];

        if (is_null($shirtsize)) {
            return null;
        }

        return in_array($shirtsize, $sizes) ? $shirtsize : array_get($sizes, $shirtsize);
    }

    public function getSchoolAttribute()
    {
        return $this->ticket_data('school');
    }

    public function getRoommateRequestedAttribute()
    {
        return $this->ticket_data('roommate_requested');
    }

    public function getLeaderAttribute()
    {
        return $this->ticket_data('leader');
    }

    public function getBusAttribute()
    {
        return $this->ticket_data('bus');
    }

    // public function ticket_data($key = null)
    // {
    //     $data = json_decode($this->ticket_data, true);

    //     return is_null($key) ? $data : array_get($data, $key);
    // }

    public function revision()
    {
        // get fresh revision info if it hasnt been loaded
        if (! $this->relationLoaded('latestRevision')) {
            $this->load('latestRevision');
        }

        $logger = static::getRevisionableLogger();
        $table = $this->getTable();
        $id    = $this->getKey();
        $user  = Auth::user();
        $latest = $this->latestRevision ? $this->latestRevision->new : [];
        $current = $this->getNewAttributes() + ['fname' => $this->first_name, 'lname' => $this->last_name];

        $logger->revisionLog('revision', $table, $id, $latest, $current, $user);

        // unset relation so that fresh revision info will be pulled
        unset($this->relations['latestRevision']);
    }

    public function getHasChangedSinceLastRevisionAttribute()
    {
        if (! $this->latestRevision) {
            return true;
        }

        if (! empty($this->latestRevision->getDiff())) {
            return true;
        }

        return false;
    }

    public function getIsCheckedInAttribute()
    {
        return (bool) $this->checked_in_at;
    }

    public function setIsCheckedInAttribute($is_checked_in)
    {
        $this->checked_in_at = $is_checked_in ? \Carbon\Carbon::now() : null;
    }

    public function checkIn()
    {
        $this->checked_in_at = \Carbon\Carbon::now();
        $this->save();
    }

    public function getPccWaiverAttribute()
    {
        return $this->ticket_data('pcc_waiver');
    }

    public function getHasPccWaiverAttribute()
    {
        return $this->pcc_waiver == 'X';
    }

    public function cancel(User $user = null)
    {
        $this->canceled_at = \Carbon\Carbon::now();
        $this->canceled_by_id = $user->id;
        $this->save();
    }
}
