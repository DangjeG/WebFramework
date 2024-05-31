<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Factory\ResponseFactory;
use Dangje\WebFramework\Factory\ServerRequestFactory;
use Dangje\WebFramework\Message\Response;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\ServerRequestInterface;

require dirname(__DIR__) . '/vendor/autoload.php';


$config = require '../src/app/DI/dependencies.php';
$container = new Container($config);


$serverRequestFactory = new ServerRequestFactory($container);
$responseFactory = new ResponseFactory();

$app = new App($serverRequestFactory, $responseFactory);

$app->add('GET', '/login', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    return $resp->withBody(new Stream('./registerAndLogin.php'));
});

$app->add('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');

    $bodyStr = json_encode($request->getQueryParams());

    $resp->withHeader("Content-Type", "application/json");
    return $resp->withBody(new Stream(data: $bodyStr));
});

$app->setMiddlewareHandler('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    if(isset($request->getQueryParams()['ddd'])
        && $request->getQueryParams()['ddd'] == '123'){
        $resp = $resp->withBody(new Stream(data: 'Hello Guest!'));
    }
    else {
        $resp = $resp->withBody(new Stream(data: 'fuck u'));
        $resp = $resp->withStatus(302, "fuck u");
    }
    return $resp;
});

$resp = $app->run();

http_response_code($resp->getStatusCode());

echo $resp->getBody()->getContents();
