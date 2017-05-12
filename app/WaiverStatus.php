<?php

namespace App;

class WaiverStatus
{
    const CREATED = 'new';
    const PENDING = 'pending';
    const COMPLETE = 'complete';

    public static function get($status)
    {
        switch (strtoupper($status)) {
            case 'NEW':
                return static::NEW;

            case 'COMPLETE':
            case 'ESIGNED':
            case 'SIGNED':
                return static::COMPLETE;

            case 'PENDING':
            default:
                return static::PENDING;
        }
    }
}
