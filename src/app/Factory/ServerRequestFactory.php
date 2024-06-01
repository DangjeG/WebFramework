<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\Message\ServerRequest;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class ServerRequestFactory implements ServerRequestFactoryInterface
{

    public function createServerRequest(string $method = '', $uri = '', array $serverParams = []): ServerRequestInterface
    {
        $uriFactory = new UriFactory();
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
            $_GET,
            $_POST,
        );
    }
    private function getBody(): StreamInterface
    {
        return new Stream('php://input');
    }
}