<?php

namespace App;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class OccurenceManager
{
    protected $app;

    protected $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    public function __get($name)
    {
        static $occurrence;

        $occurrence = new Occurrence($this->getConfig());

        return $occurrence->$name;
    }

    protected function getConfig()
    {
        $slug = $this->request->input('event');

        return $this->app['config']["occurrences.{$slug}"];
    }
}
