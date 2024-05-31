<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Factory\RequestFactory;
use Dangje\WebFramework\Factory\ResponseFactory;
use Dangje\WebFramework\Factory\ServerRequestFactory;
use Dangje\WebFramework\Factory\UriFactory;
use Dangje\WebFramework\Message\Response;
use Dangje\WebFramework\ErrorHandler;
use Dangje\WebFramework\Message\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Dangje\WebFramework\Handler\Request;

require dirname(__DIR__) . '/vendor/autoload.php';


$container = new Container();
$container->set(new ResponseFactory());
$container->set(new RequestFactory($container));
$container->set(new UriFactory());
$container->set(new ServerRequestFactory($container));

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

require dirname(__DIR__) . '/vendor/autoload.php';

$conteiner = new Container();
$config = require '../src/app/DI/dependencies.php';
$container = new Container($config);

$car = $container->get('Car');

echo $car->start();

/*$app = new App();
echo $app->run();
*/
//$entityBody = file_get_contents('php://input');

$error = new ErrorHandler();
$error->register();

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

$st = new Stream('php://input');

// $st = new Stream('php://input');


// echo $st->getSize();

// echo $entityBody;

//echo $undefined_var;

//undefined_function();

throw new \InvalidArgumentException("капец");

// $st = new Stream('php://input');

// echo $st->getSize();


// foreach ($_SERVER as $key => $value) {
//     if(is_array($value)){
//         echo '<pre>';
//         echo "{$key} => ... ";
//         echo '</pre>';
//         foreach ($value as $key1 => $value1) {
//             echo '<pre>';
//             echo "{$key1} => {$value1} ";
//             echo '</pre>';
//         }
//     }
//     echo '<pre>';
//     echo "{$key} => {$value} ";
//     echo '</pre>';
// }


// foreach ($_GET as $key => $value) {
//     echo '<pre>';
//     echo "{$key} => {$value} ";
//     echo '</pre>';
// }

// function some ()
// {
//     return "ret";
// }
// }
