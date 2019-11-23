<?php

namespace App;

class WaiverStatus
{
    const CREATED = 'new';
    const PENDING = 'pending';
    const COMPLETE = 'complete';
    const BOUNCED = 'bounced';

    public static function get($status)
    {
        switch (strtoupper($status)) {
            case 'NEW':
                return static::CREATED;
            case 'COMPLETE':
            case 'ESIGNED':
            case 'SIGNED':
                return static::COMPLETE;
            case 'BOUNCED':
                return static::BOUNCED;
            case 'PENDING':
            default:
                return static::PENDING;
        }
    }
}
