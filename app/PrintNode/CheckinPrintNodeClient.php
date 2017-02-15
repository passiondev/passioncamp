<?php

namespace App\PrintNode;

class CheckinPrintNodeClient
{
    public static function init()
    {
        $credentials = new \PrintNode\Credentials\ApiKey(env('CHECKIN_PRINTNODE_API_KEY'));

        return new \PrintNode\Client($credentials);
    }
}
