<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\Factory\ResponseFactory;
use Dangje\WebFramework\Factory\ServerRequestFactory;
use Dangje\WebFramework\Message\Response;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\ServerRequestInterface;

require dirname(__DIR__) . '/vendor/autoload.php';


$serverRequestFactory = new ServerRequestFactory();
$responseFactory = new ResponseFactory();

$app = new App($serverRequestFactory, $responseFactory);

$app->add('GET', '/logi', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    return $resp->withBody(new Stream('./Auth/Auth.php'));
});

$app->add('POST', '/logi', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    return $resp->withBody(new Stream(data: json_encode($request->getParsedBody())));
});



$app->add('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');

    $bodyStr = json_encode($request->getQueryParams());

    $resp->withHeader("Content-Type", "application/json");
    return $resp->withBody(new Stream('./MainPage/MainPage.php'));
});

$app->setMiddlewareHandler('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    if(isset($request->getQueryParams()['ddd'])
        && $request->getQueryParams()['ddd'] == '123'){
        $resp = $resp->withBody(new Stream(data: ''));
    }
    else {
        $resp = $resp->withBody(new Stream('./MainPage/HiddenMainPage.php'));
        $resp = $resp->withStatus(302, "not authenticated");
    }
    return $resp;
});

$resp = $app->run();

http_response_code($resp->getStatusCode());

echo $resp->getBody()->getContents();

