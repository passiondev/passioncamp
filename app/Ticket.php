<?php

namespace App;

use App\Waiver;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
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

    public function waiver()
    {
        return $this->hasOne(Waiver::class);
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
    }
}
