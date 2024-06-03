<?php

namespace Dangje\WebFramework\Handler;

use Dangje\WebFramework\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher
{
    private array $middlewares;
    private int $iteration = 0;

    public function __construct(array $middlewares = []){
        $this->middlewares = $middlewares;
    }

    public function add(MiddlewareInterface $middleware): void{
        $this->middlewares[] = $middleware;
    }

    public function getNext(): MiddlewareInterface | null {
        if($this->iteration < count($this->middlewares)){
            $middleware = $this->middlewares[$this->iteration];
            $this->iteration = $this->iteration + 1;
            return $middleware;
        }
        return null;
    }

    public function hasNext(): bool{
        return isset($this->middlewares[$this->iteration]);
    }

    public function dispatch(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface{
        if($this->hasNext()){
            return $this->getNext()->process($request, $handler);
        }
        return $handler->handle($request);
    }
}