<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Church extends Model
{
    use SoftDeletes;
    
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
