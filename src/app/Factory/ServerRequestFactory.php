<?php

namespace Dangje\WebFramework\Factory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{

    #[\Override] public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        // TODO: Implement createServerRequest() method.
    }
}