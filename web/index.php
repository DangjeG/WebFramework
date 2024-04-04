<?php

use Dangje\WebFramework\App;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
echo $app->run();