<?php

namespace App\PrintNode;

class RoominglistPrintNodeClient
{
    public static function init()
    {
        $credentials = new \PrintNode\Credentials\ApiKey(env('ROOMINGLIST_PRINTNODE_API_KEY'));

        return new \PrintNode\Client($credentials);
    }
}
