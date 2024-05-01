<?php

namespace Dangje\WebFramework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class App implements RequestHandlerInterface
{
    public function run(): string
    {
        return "";
    }

    #[\Override] public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
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