<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Middelware implements MiddlewareInterface{

    #[\Override] public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{
        return $handler->handle($request);
    }
}