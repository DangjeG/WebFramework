<?php

namespace Dangje\WebFramework\DI;

class Container
{
    private array $services = [];

    public function set($service): void
    {
        $this->services[$service::class] = $service;
    }

    public function get($id): object
    {
        if (!isset($this->services[$id])) {
            throw new \InvalidArgumentException('No service registered for id ' . $id);
        }
        return $this->services[$id];
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }
}