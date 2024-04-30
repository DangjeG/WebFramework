<?php

namespace Dangje\WebFramework\Factory;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory implements UriFactoryInterface
{

    #[\Override] public function createUri(string $uri = ''): UriInterface
    {

    }
}