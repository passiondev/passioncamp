<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organization';
    
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
}
