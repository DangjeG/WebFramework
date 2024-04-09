<?php

use Dangje\WebFramework\App;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
echo $app->run();

/*
$entityBody = file_get_contents('php://input');


echo $entityBody;


foreach ($_SERVER as $key => $value) {
    echo '<pre>';
    echo "{$key} => {$value} ";
    echo '</pre>';
}
*/