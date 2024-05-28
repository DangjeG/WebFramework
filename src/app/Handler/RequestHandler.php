<?php

namespace Dangje\WebFramework\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestHandler implements RequestHandlerInterface {


    public $handleFunc;

    public function __construct(callable $handleFunc)
    {
        $this->handleFunc = $handleFunc;
    }

    #[\Override] public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->handleFunc)($request);
    }
}
