<?php

use Dangje\WebFramework\Handler\RequestHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route {

    private string $method;
    private string $path;
    private RequestHandlerInterface $handler;

    public function __construct(string $method, string $path, RequestHandlerInterface $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
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
}