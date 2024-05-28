<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Message\ServerRequest;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private Container $container;

    public function __construct(Container $container){
        $this->container = $container;
    }
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest(requestTarget: '', method: $method, uri: $uri, serverParams: $serverParams);
    }
    public function createServerRequestFromGlobals(): ServerRequestInterface{
        $uriFactory = $this->container->get(UriFactoryInterface::class);
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $uriFactory->createUri($_SERVER['REQUEST_URI']);

        return new ServerRequest(
            "/",
            $method,
            $uri,
            $_SERVER,
            getallheaders(),
            $this->getBody(),
            $_COOKIE,
            $_FILES,
            [],
        );
    }

    private function getBody(): StreamInterface
    {
        return new Stream('php://input');
    }
}