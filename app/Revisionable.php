<?php

namespace App;

use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Traits\LogsActivity;

trait Revisionable
{
    use LogsActivity;

    protected static $recordEvents = [];

    public function revision()
    {
        $lastActivity = $this->activity()->latest()->first();

        $properties['attributes'] = static::logChanges($this);
        $properties['old'] = optional($lastActivity)->changes()['attributes'] ?? [];
        $properties['old'] = array_diff_assoc($properties['old'], $properties['attributes']);
        $properties['changed'] = $lastActivity ? array_keys($properties['old']) : static::attributesToBeLogged();

        return app(ActivityLogger::class)
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
