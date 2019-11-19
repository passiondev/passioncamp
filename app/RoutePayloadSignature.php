<?php

namespace App;

class RoutePayloadSignature
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function __toString()
    {
        return $this->generate();
    }

    public static function create($payload)
    {
        return (new static($payload))->generate();
    }

    public function generate()
    {
        return hash_hmac('sha256', json_encode($this->payload), config('app.key'));
    }
}
