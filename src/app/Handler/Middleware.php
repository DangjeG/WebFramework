<?php

namespace Dangje\WebFramework\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Middleware implements MiddlewareInterface{

    private RequestHandlerInterface $requestHandler;
    private MiddlewareDispatcher  $dispatcher;

    public function __construct(RequestHandlerInterface $requestHandler, MiddlewareDispatcher $dispatcher)
    {
        $this->requestHandler = $requestHandler;
        $this->dispatcher = $dispatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{

        $response = $this->requestHandler->handle($request);

        if($response->getStatusCode() > 299 || $response->getStatusCode() < 200) {
            return $response;
        }

        if ($this->dispatcher->hasNext())
            return $this->dispatcher->getNext()->process($request, $handler);

        return $handler->handle($request);
    }
}