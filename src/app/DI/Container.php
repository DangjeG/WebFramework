<?php

namespace Dangje\WebFramework\DI;

use Psr\Container\ContainerInterface;

/**
 * Example config file
 * return [
 *      'Car' => function (\Psr\Container\ContainerInterface $container) {
 *          return new Dangje\WebFramework\DI\Car($container->get('Engine'));
 *      },
 *      'Engine' => function () {
 *          return new \Dangje\WebFramework\DI\Engine();
 *      }
 *  ];
 */

class Container implements ContainerInterface
{
    private $definitions = [];
    private $instances = [];

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function get($id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->definitions[$id])) {
            $this->instances[$id] = $this->definitions[$id]($this);
            return $this->instances[$id];
        }

        throw new \Exception("Service not found: " . $id);
    }

    public function has($id): bool
    {
        return isset($this->definitions[$id]);
    }
}