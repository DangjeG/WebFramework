<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Factory\RequestFactory;
use Dangje\WebFramework\Factory\ResponseFactory;
use Dangje\WebFramework\Factory\ServerRequestFactory;
use Dangje\WebFramework\Factory\UriFactory;
use Dangje\WebFramework\Message\Response;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\RequestInterface;

require dirname(__DIR__) . '/vendor/autoload.php';


$container = new Container();
$container->set(new ResponseFactory());
$container->set(new RequestFactory($container));
$container->set(new UriFactory());
$container->set(new ServerRequestFactory($container));

$serverRequestFactory = new ServerRequestFactory($container);
$responseFactory = new ResponseFactory();

$app = new App($serverRequestFactory, $responseFactory);

$app->add('GET', '/', function (RequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    return $resp->withBody(new Stream(data: 'Hello World!'));
});

$resp = $app->run();

http_response_code($resp->getStatusCode());

echo $resp->getBody()->getContents();
