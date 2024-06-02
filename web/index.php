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
$salt = "fdlkgmlklk";

$app = new App($serverRequestFactory, $responseFactory);

session_start();

$app->add('GET', '/auth', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');
    return $resp->withBody(new Stream('./Auth/Auth.php'));
});

$app->add('GET', '/logout', function (ServerRequestInterface $request) {

    $resp = new Response(200, 'Hello World!');
    setcookie('Authorization', '');

    return $resp->withBody(new Stream('./Auth/Auth.php'));
});

$app->add('POST', '/login', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'OK');

    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['pswd'];
    $salt = "fdlkgmlklk";

    $emailHash = crypt($email, $salt);

    if(isset($_SESSION[$emailHash])){
        
        $userData = explode(';', $_SESSION[$emailHash], 2);
        if($password == $userData[1]){
            setcookie('Authorization', $emailHash);
            $resp = $resp->withStatus(200, 'OK');
            $resp = $resp->withBody(new Stream('./MainPage/MainPage.php'));

        }
        else{
            $resp = $resp->withStatus(401, 'Invalid password');
            $resp = $resp->withBody(new Stream('./Auth/Auth.php'));
        }
    }
    else{
        $resp = $resp->withStatus(401, 'User not exist');
        $resp = $resp->withBody(new Stream('./Auth/Auth.php'));
    }

    return $resp;
});

$app->add('POST', '/signin', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'Hello World!');

    $email = $request->getParsedBody()['email'];
    $password = $request->getParsedBody()['pswd'];
    $salt = "fdlkgmlklk";


    $emailHash = crypt($email, $salt);
    if(!isset($_SESSION[$emailHash])){
        $_SESSION[$emailHash] = implode(';', [$email, $password]);
        setcookie('Authorization', $emailHash);

        $resp = $resp->withStatus(200, 'OK');
        $resp = $resp->withBody(new Stream('./MainPage/MainPage.php'));
    }
    else{
        $resp = $resp->withStatus(409, "User already exist");
        $resp = $resp->withBody(new Stream('./Auth/Auth.php'));
    }

    return $resp;
});


$app->add('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'OK');
    return $resp->withBody(new Stream('./MainPage/MainPage.php'));
});


$app->setMiddlewareHandler('GET', '/', function (ServerRequestInterface $request) {
    $resp = new Response(200, 'OK');
    if(isset($request->getCookieParams()['Authorization'])){
        if(isset($_SESSION[$request->getCookieParams()['Authorization']]))
            return $resp;
    }
    $resp = $resp->withStatus(401, "not authenticated");
    $resp = $resp->withBody(new Stream('./MainPage/HiddenMainPage.php'));
    return $resp;
});



$resp = $app->run();

http_response_code($resp->getStatusCode());

echo $resp->getBody()->getContents();

