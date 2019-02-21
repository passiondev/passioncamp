<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class OccurenceManager
{
    protected $app;

    protected $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    protected function getConfig()
    {
        $slug = $this->request->input('event');

        return $this->app['config']["occurrences.{$slug}"];
    }

    public function __get($name)
    {
        static $occurrence;

        $occurrence = new Occurrence($this->getConfig());

        return $occurrence->$name;
    }
}
