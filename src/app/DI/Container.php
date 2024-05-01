<?php

namespace Dangje\WebFramework\DI;

class Container
{
    private $services = [];

    public function set(string $id, $service): void
    {
        $this->services[$id] = $service;
    }

    public function get(string $id): object
    {
        if (!isset($this->services[$id])) {
            throw new \InvalidArgumentException('No service registered for id ' . $id);
        }
        return $this->services[$id];
    }
}