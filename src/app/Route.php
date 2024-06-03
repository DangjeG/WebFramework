<?php

namespace Dangje\WebFramework;

use Dangje\WebFramework\Handler\Middleware;
use Dangje\WebFramework\Handler\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route {

    private string $method;
    private string $path;
    private RequestHandlerInterface $handler;
    private MiddlewareDispatcher  $dispatcher;

    public function __construct(
        string $method,
        string $path,
        RequestHandlerInterface $handler,
        array $middlewares = [],
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->dispatcher = new MiddlewareDispatcher($middlewares);
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

    public function withHandler(RequestHandlerInterface $handler): Route
    {
        $new = clone $this;
        $new->handler = $handler;
        return $new;
    }

    public function addMiddleware(RequestHandlerInterface $handler): void
    {
        $this->dispatcher->add(new Middleware($handler, $this->dispatcher));
    }

    public function isMatch(ServerRequestInterface $request): bool
    {
        return $request->getMethod() === $this->getMethod() && $request->getUri()->getPath() === $this->getPath();

    }

    public function process(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatcher->dispatch($request, $this->handler);
    }
}