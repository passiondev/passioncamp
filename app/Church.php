<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    protected $table = 'church';

    protected $fillable = ['name', 'street', 'city', 'state', 'zip', 'website', 'pastor_name'];

    public function getLocationAttribute()
    {
        if (strlen($this->city)) {
            return sprintf('%s, %s', $this->city, $this->state);
        }

        return '';
    }

}
