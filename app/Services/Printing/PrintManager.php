<?php

namespace App\Services\Printing;

use PrintNode;

class PrintManager
{
    protected $app;
    protected $drivers = [];

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->drivers[$name] = $this->get($name);
    }

    public function getDefaultDriver()
    {
        return $this->app['config']['printing.default'];
    }

    public function setDefaultDriver($name)
    {
        $this->app['config']['printing.default'] = $name;
    }

    protected function get($name)
    {
        return isset($this->drivers[$name]) ? $this->drivers[$name] : $this->resolve($name);
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (null === $config) {
            throw new InvalidArgumentException("Printer [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (! method_exists($this, $driverMethod)) {
            throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
        }

        return $this->{$driverMethod}($config);
    }

    protected function createPrintnodeDriver(array $config)
    {
        $credentials = new PrintNode\Credentials\ApiKey($config['key']);

        return new PrintNodePrinter(
            new PrintNode\Client($credentials),
            $config['key']
        );
    }

    // protected function createLogDriver(array $config)
    // {
    //     return new LogBroadcaster(
    //         $this->app->make(LoggerInterface::class)
    //     );
    // }

    // protected function createNullDriver(array $config)
    // {
    //     return new NullBroadcaster;
    // }

    protected function getConfig($name)
    {
        return $this->app['config']["printing.clients.{$name}"];
    }
}
