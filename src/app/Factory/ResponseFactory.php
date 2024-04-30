<?php

namespace Dangje\WebFramework\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{

    #[\Override] public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response();
    }
}