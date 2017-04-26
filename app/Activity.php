<?php

namespace App;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{
    public function hasChanges()
    {
        return (bool) count($this->properties['changed']);
    }
}
