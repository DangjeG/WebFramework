<?php

use Dangje\WebFramework\App;

require dirname(__DIR__) . '/vendor/autoload.php';

/*$app = new App();
echo $app->run();
*/

$entityBody = file_get_contents('php://input');


echo $entityBody;

foreach ($_SERVER as $key => $value) {
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


foreach ($_GET as $key => $value) {
    echo '<pre>';
    echo "{$key} => {$value} ";
    echo '</pre>';
}

function some ()
{
    return "ret";
}