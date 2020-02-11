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
        if (! property_exists($this, 'alias') || empty($this->alias)) {
            return;
        }

        return \array_key_exists($key, $this->alias);
    }

    public function getAliasAttribute($key)
    {
        return $this->getAttribute($this->alias[$key]);
    }

    protected function castAttribute($key, $value)
    {
        if (null === $value) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'dollar':
            case 'dollars':
                return $value / 100;
            default:
                return parent::castAttribute($key, $value);
        }
    }
}
