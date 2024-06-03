<?php

namespace Dangje\WebFramework;

use Dangje\WebFramework\Handler\Middleware;
use Dangje\WebFramework\Handler\RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;

class App 
{
    private array $routes;
    private ServerRequestFactoryInterface $serverRequestFactory;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ServerRequestFactoryInterface $serverRequestFactory, ResponseFactoryInterface $responseFactory)
    {
        $this->serverRequestFactory = $serverRequestFactory;
        $this->responseFactory = $responseFactory;
        $this->routes = [];
    }

    public function add( string $method, $path, callable $handleFunc): void
    {
        $this->routes[] = new Route($method, $path, new RequestHandler($handleFunc));
    }

    public function setHandler(string $method, $path, callable $handleFunc): void
    {
        $request = $this->serverRequestFactory->createServerRequest($method, $path);
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                $route = $route->withHandler(new RequestHandler($handleFunc));
                return;
            }
        }
        $this->routes[] = new Route($method, $path, new RequestHandler($handleFunc));
    }

    public function setMiddlewareHandler(string $method, $path, callable $handleFunc): void
    {
        $request = $this->serverRequestFactory->createServerRequest($method, $path);
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                $route->addMiddleware(new RequestHandler($handleFunc));
                return;
            }
        }
    }

    public function setMiddleware(string $method, $path, MiddlewareInterface $middleware): void
    {
        $request = $this->serverRequestFactory->createServerRequest($method, $path);
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                $route = $route->withMiddleware($middleware);
                return;
            }
        }
    }

    public function run(): ResponseInterface
    {
        $request = $this->serverRequestFactory->createServerRequest();
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                return $route->process($request);
            }
        }
        return $this->responseFactory->createResponse(404, 'Not Found');
    }
}