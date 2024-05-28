<?php

namespace Dangje\WebFramework;

use Dangje\WebFramework\Handler\RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;

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

    public function add( string $path, $method, callable $handleFunc): void
    {
        $this->routes[] = new Route($path, $method, new RequestHandler($handleFunc));
    }

    public function run(): ResponseInterface
    {
        $request = $this->serverRequestFactory->createServerRequestFromGlobals();
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                return $route->handle();
            }
        }
        return $this->responseFactory->createResponse(404, 'Not Found');
    }
}



// собственный фреймворк с парой страничек, авторизация на варе, обработка ошибок, настройки, сервис локатор, диай
// 1) только пср пакеты
// 2) слоистая архитектура
// 3) диай
// 4) авторизация
// 5) Сессии бирер токен
// 6) ответ на основе заголовка ассепт
// 7) обработка запросов на основе контент тайп
// 8) обработка ошибок минимум 500 и 404
// 9) шаблонизатор для вывода ответа
// 10) 2-3 странички или тесты
// 11) кодсниффер желательно линтер