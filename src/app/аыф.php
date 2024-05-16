<?php
// app/config/composer.json
{
    "name": "app/config",
    "type": "library",
    "require": {
    "psr/container": "^1.1",
        "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0"
    }
}


// app/config/src/Container.php
class Container implements ContainerInterface
{
    private $services = [];

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new RuntimeException("Service $id not found.");
        }

        return $this->services[$id];
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function set($id, $service): void
    {
        $this->services[$id] = $service;
    }
}

// app/config/src/ServiceLocator.php
class ServiceLocator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id): bool
    {
        return $this->container->has($id);
    }
}

// app/router/composer.json
/*{
    "name": "app/router",
    "type": "library",
    "require": {
    "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0"
    }
}*/

// app/router/src/Router.php
class Router
{
    private $routes = [];

    public function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = new Route($method, $path, $handler);
    }

    public function match(RequestInterface $request): RouteMatchResultInterface
    {
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                return new RouteMatchResult($route->getHandler());
            }
        }

        return new RouteMatchResult(null);
    }
}

// app/router/src/Route.php
class Route
{
    private $method;
    private $path;
    private $handler;

    public function __construct(string $method, string $path, $handler)
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

    public function getHandler()
    {
        return $this->handler;
    }

    public function isMatch(RequestInterface $request): bool
    {
        return $request->getMethod() === $this->getMethod() && $request->getUri()->getPath() === $this->getPath();
    }
}

// app/router/src/RouteMatchResult.php
class RouteMatchResult implements RouteMatchResultInterface
{
    private $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function isMatch(): bool
    {
        return $this->handler !== null;
    }
}

// app/router/src/RouteMatchResultInterface.php
interface RouteMatchResultInterface
{
    public function getHandler();

    public function isMatch(): bool;
}

// app/error-handler/composer.json
{
    "name": "app/error-handler",
    "type": "library",
    "require": {
    "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0"
    }
}

// app/error-handler/src/ErrorHandler.php
class ErrorHandler
{
    private $templateEngine;

    public function __construct(TemplateEngineInterface $templateEngine)
    {
        $this->templateEngine = $templateEngine;
    }

    public function handle(Throwable $exception, RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $errorPage = $this->templateEngine->render('error.php', [
            'exception' => $exception
        ]);

        $response->getBody()->write($errorPage);
        $response = $response->withStatus($exception->getCode());

        return $response;
    }
}

// app/middleware/composer.json
{
    "name": "app/middleware",
    "type": "library",
    "require": {
    "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0"
    }
}

// app/middleware/src/AuthenticationMiddleware.php
class AuthenticationMiddleware
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        // Проверка авторизации пользователя
        if (!$this->isAuthorized($request)) {
            // Перенаправление на страницу авторизации
            return $response->withStatus(401)->withHeader('Location', '/login');
        }

        // Продолжение обработки запроса
        return $next($request, $response);
    }

    protected function isAuthorized(RequestInterface $request): bool
    {
        // Реализация проверки авторизации
        return true;
    }
}

// app/middleware/src/ContentNegotiationMiddleware.php
class ContentNegotiationMiddleware
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        // Построение ответа в зависимости от заголовка Accept
        $acceptHeader = $request->getHeaderLine('Accept');
        $contentType = $this->getContentType($acceptHeader);

        $response = $response->withHeader('Content-Type', $contentType);

        // Продолжение обработки запроса
        return $next($request, $response);
    }

    protected function getContentType(string $acceptHeader): string
    {
        // Реализация согласования содержимого
        return 'text/html';
    }
}

// app/middleware/src/MiddlewareInterface.php
interface MiddlewareInterface
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface;
}

// app/middleware/src/MiddlewareStack.php
class MiddlewareStack
{
    private $middleware = [];

    public function add(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $middleware = array_shift($this->middleware);

        if ($middleware) {
            return $middleware($request, $response, $next);
        }

        return $next($request, $response);
    }
}

// app/template-engine/composer.json
{
    "name": "app/template-engine",
    "type": "library",
    "require": {
    "psr/http-factory": "^1.0",
        "psr/http-message": "^1.0"
    }
}

// app/template-engine/src/TemplateEngineInterface.php
interface TemplateEngineInterface
{
    public function render(string $template, array $data = []): string;
}

// app/template-engine/src/BladeTemplateEngine.php
class BladeTemplateEngine implements TemplateEngineInterface
{
    private $loader;
    private $compiler;
    private $cache;

    public function __construct(LoaderInterface $loader, CompilerInterface $compiler, CacheInterface $cache)
    {
        $this->loader = $loader;
        $this->compiler = $compiler;
        $this->cache = $cache;
    }

    public function render(string $template, array $data = []): string
    {
        $compiledTemplate = $this->compileTemplate($template);

        ob_start();
        extract($data);
        eval('?>' . $compiledTemplate);
        $output = ob_get_clean();

        return $output;
    }

    protected function compileTemplate(string $template): string
    {
        $cachedTemplate = $this->cache->get($template);

        if (!$cachedTemplate) {
            $source = $this->loader->load($template);
            $compiledTemplate = $this->compiler->compile($source);
            $this->cache->set($template, $compiledTemplate);
        }

        return $cachedTemplate ?: $compiledTemplate;
    }
}