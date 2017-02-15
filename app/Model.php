<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected function asDateTime($value)
    {
        return parent::asDateTime($value)->timezone('America/New_York');
    }
}
