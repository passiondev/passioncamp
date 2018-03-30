<?php

namespace App;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{
    public function propertiesHaveChanged()
    {
        return (bool) count($this->properties['changed']);
    }
}
