<?php

namespace Dangje\WebFramework\Factory;

use Dangje\WebFramework\Message\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = 'OK'): ResponseInterface
    {
        return new Response($code, $reasonPhrase);
    }

}