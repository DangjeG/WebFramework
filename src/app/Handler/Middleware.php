<?php

namespace Dangje\WebFramework\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Middleware implements MiddlewareInterface{

    private array $handlers;

    public function __construct(array $handlers)
    {
        $this->$handlers = $handlers;
    }

    public function addHandler(RequestHandler $next): void
    {
        $this->handlers[] = $next;
    }

    #[\Override] public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{

        foreach ($this->handlers as $nextHandler) {
            $response = $nextHandler->handle($request);
            if ($response->getStatusCode() != 200) {
                return $response;
            }
        }
        return $handler->handle($request);
    }
}