<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Factory\RequestFactory;
use Dangje\WebFramework\Factory\ResponseFactory;
use Dangje\WebFramework\Factory\ServerRequestFactory;
use Dangje\WebFramework\Factory\UriFactory;
use Dangje\WebFramework\Message\Response;
use Dangje\WebFramework\Message\Stream;

require dirname(__DIR__) . '/vendor/autoload.php';

/*$app = new App();
echo $app->run();
*/
//$entityBody = file_get_contents('php://input');


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




$st = new Stream('php://input');

echo $st->getSize();

// echo $st->getSize();

// echo $entityBody;

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


function some ()
{
    return "ret";
}