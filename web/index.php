<?php

use Dangje\WebFramework\App;
use Dangje\WebFramework\Message\Stream;

require dirname(__DIR__) . '/vendor/autoload.php';

/*$app = new App();
echo $app->run();*/



$st = new Stream('php://input');




foreach ($_COOKIE as $key => $value) {
     if(is_array($value)){
         echo '<pre>';
         echo "{$key} => ... ";
         echo '</pre>';
         foreach ($value as $key1 => $value1) {
             echo '<pre>';
             echo "{$key1} => {$value1} ";
             echo '</pre>';
         }
     }
     echo '<pre>';
     echo "{$key} => {$value} ";
     echo '</pre>';
 }


/* foreach ($_GET as $key => $value) {
     echo '<pre>';
     echo "{$key} => {$value} ";
     echo '</pre>';
 }*/
