<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\Handler\Request;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{

    #[\Override] public function createRequest(string $method, $uri): RequestInterface
    {
        return new Request($method, $uri);
    }
}