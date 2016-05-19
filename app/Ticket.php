<?php

namespace App;

use Auth;
use App\Waiver;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Builder;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends OrderItem
{
    use Eloquence, FormAccessible, SoftDeletes;

    protected $table = 'order_item';
    
    protected $type = 'ticket';

    protected $searchableColumns = ['id', 'person.first_name', 'person.last_name'];

    protected $fillable = [
        'agegroup',
        'price'
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

        if ($user->is_super_admin) {
            return $query;
        }

        return $query->where('organization_id', $user->organization_id);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('room_id');
    }

    public function waiver()
    {
        return $this->hasOne(Waiver::class);
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

    /*-------------- setters -----------------*/
    public function setTicketDataAttribute($ticket_data)
    {
        if (is_array($ticket_data)) {
            $ticket_data = json_encode($ticket_data);
        }

        $this->attributes['ticket_data' ] = $ticket_data;

        return $this;
    }

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

    public function ticket_data($key = null)
    {
        $data = json_decode($this->ticket_data, true);

        return is_null($key) ? $data : array_get($data, $key);
    }
}
