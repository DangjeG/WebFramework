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

    public function __construct(
        string $method,
        string $path,
        RequestHandlerInterface $handler,
        MiddlewareInterface $middleware = null,
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
        $this->middleware = is_null($middleware) ? new Middleware() : $middleware;
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

    public function getMiddleware(): MiddlewareInterface
    {
        return $this->middleware;
    }

    public function withMiddleware(MiddlewareInterface $middleware): Route
    {
        $new = clone $this;
        $new->middleware = $middleware;
        return $new;
    }

    public function withAddedMiddlewareHandler(RequestHandlerInterface $handler): Route
    {
        $new = clone $this;
        $new->middleware = $this->middleware->withAddedHandler($handler);
        return $new;
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