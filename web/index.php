<?php

use Dangje\WebFramework\Message\Stream;
use Dangje\WebFramework\App;
use Dangje\WebFramework\DI\Container;
use Dangje\WebFramework\Handler\Request;

require dirname(__DIR__) . '/vendor/autoload.php';

$conteiner = new Container();

$request = $conteiner->get(Request::class);

echo $request->getMethod();

/*$app = new App();
echo $app->run();
*/

//$entityBody = file_get_contents('php://input');


$st = new Stream('php://input');

echo $st->getSize();

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


// foreach ($_GET as $key => $value) {
//     echo '<pre>';
//     echo "{$key} => {$value} ";
//     echo '</pre>';
// }

// function some ()
// {
//     return "ret";
// }