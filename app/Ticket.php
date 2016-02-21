<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends OrderItem
{
    protected $table = 'order_item';
    
    protected $type = 'ticket';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', '=', 'ticket');
        });
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /*-------------- getters -----------------*/

    /*-------------- setters -----------------*/
    public function setTicketDataAttribute($ticket_data)
    {
        if (is_array($ticket_data)) {
            $ticket_data = json_encode($ticket_data);
        }

        $this->attributes['ticket_data' ] = $ticket_data;
    }
}
