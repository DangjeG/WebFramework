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
    public function createServerRequest(string $method = '', $uri = '', array $serverParams = []): ServerRequestInterface
    {
        $uriFactory = $this->container->get(UriFactory::class);
        $method =
            $method != ''  ? $method : $_SERVER['REQUEST_METHOD'];;
        $uri = $uriFactory->createUri(
            $uri != '' ? $uri : $_SERVER['REQUEST_URI']);

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
            $_GET
        );
    }
    private function getBody(): StreamInterface
    {
        return new Stream('php://input');
    }
}