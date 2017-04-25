<?php

namespace App;

use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Traits\LogsActivity;

trait Revisionable
{
    use LogsActivity;

    protected static $recordEvents = [
        'created'
    ];

    public function revision()
    {
        $properties['attributes'] = static::logChanges($this);
        $properties['old'] = $this->activity()->latest()->first()->changes['attributes'];
        $properties['old'] = collect($properties['old'])->diff($properties['attributes'])->all();
        // $properties['changed'] = array_keys($properties['old']);

        app(ActivityLogger::class)
            ->useLog($this->getLogNameToUse())
            ->performedOn($this)
            ->withProperties($properties)
            ->log('revision');
    }

    protected function getLogNameToUse()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

}
