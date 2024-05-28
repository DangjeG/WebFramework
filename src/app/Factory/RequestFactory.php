<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\Message\Request;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class RequestFactory implements RequestFactoryInterface
{

    public function createRequest(string $method, $uri): RequestInterface
    {
        $requestTarget = '/';
        if (isset($_SERVER['REQUEST_TARGET']))
            $requestTarget = $_SERVER['REQUEST_TARGET'];

        return new Request(
            $requestTarget,
            $method,
            $uri,
            getallheaders(),
            $this->getBody(),
        );
    }

    private function getBody(): StreamInterface
    {
        return new Stream('php://input');
    }
}