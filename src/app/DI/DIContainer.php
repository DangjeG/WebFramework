<?php

namespace Dangje\WebFramework\DI;

use DI\Container;
use Exception;
use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;

class DIContainer implements ContainerInterface
{
    protected Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $this->container = $containerBuilder->build();
    }

    #[\Override] public function get($id)
    {
        return $this->container->get($id);
    }

    #[\Override] public function has($id): bool
    {
        return $this->container->has($id);
    }
}