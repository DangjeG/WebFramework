<?php

namespace Dangje\WebFramework;

use Dangje\WebFramework\Handler\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route {

    private string $method;
    private string $path;
    private RequestHandlerInterface $handler;
    private MiddlewareInterface $middleware;

    public function __construct(string $method, string $path, RequestHandlerInterface $handler) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middleware = new Middleware([]);
    }


    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    public function isMatch(ServerRequestInterface $request): bool
    {
        return $request->getMethod() === $this->getMethod() && $request->getUri()->getPath() === $this->getPath();
    }

    public function process(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->handler);
    }
}