<?php

namespace App;

class WaiverStatus
{
    const CREATED = 'new';
    const PENDING = 'pending';
    const COMPLETE = 'complete';

    public static function get($status)
    {
        switch ($status) {
            case 'SIGNED':
                return static::COMPLETE;

            default:
                return static::PENDING;
        }
    }
}
