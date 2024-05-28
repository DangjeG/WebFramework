<?php

namespace Dangje\WebFramework;

use Route;

class App 
{
 
    private $routes = [];


    public function add( string $path, $metod, callable $handler){
        $this->routes[] = new Route($metod, $path, $handler);
    }

    public function run(): void
    {
        
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                return $route->handle();
            }
        }
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