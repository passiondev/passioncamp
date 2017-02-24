<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function getAttribute($key)
    {
        if ($this->hasAlias($key)) {
            $this->attributes[$key] = $this->getAliasAttribute($key);

            return $this->getAttributeValue($key);
        }

        return parent::getAttribute($key);
    }

    public function hasAlias($key)
    {
        return array_key_exists($key, $this->alias);
    }

    public function getAliasAttribute($key)
    {
        return $this->getAttribute($this->alias[$key]);
    }
}
