<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\Message\Uri;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{

    public function createUri(string $uri = ''): UriInterface
    {
        $uri = $uri !== '' ? $uri :  $_SERVER['REQUEST_URI'];
        return new Uri($uri);
    }
}