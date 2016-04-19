<?php

namespace App;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Collective\Html\Eloquent\FormAccessible;

class Ticket extends OrderItem
{
    use Eloquence, FormAccessible;

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

    public function waiver()
    {
        return new Waiver($this);
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
