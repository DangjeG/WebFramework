<?php

namespace Dangje\WebFramework\Handler;

use Middlewares\HttpAuthentication;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware extends HttpAuthentication implements MiddlewareInterface {

    private $verifyHash = false;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $username = $this->login($request);

        if ($username === null) {
            return $this->responseFactory->createResponse(401)
                ->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
        }

        if ($this->attribute !== null) {
            $request = $request->withAttribute($this->attribute, $username);
        }

        return $handler->handle($request);
    }

    public function verifyHash($verifyHash = true): self
    {
        $this->verifyHash = $verifyHash;

        return $this;
    }

    private function login(ServerRequestInterface $request): ?string
    {
        $authorization = $this->parseHeader($request->getHeaderLine('Authorization'));

        if (empty($authorization)) {
            return null;
        }

        if (!isset($this->users[$authorization['username']])) {
            return null;
        }

        if ($this->verifyHash) {
            return password_verify($authorization['password'], $this->users[$authorization['username']])
                ? $authorization['username']
                : null;
        }

        return $this->users[$authorization['username']] === $authorization['password']
            ? $authorization['username']
            : null;
    }

    private function parseHeader(string $header): ?array
    {
        if (strpos($header, 'Basic') !== 0) {
            return null;
        }

        $header = base64_decode(substr($header, 6));

        if ($header === false) {
            return null;
        }

        $header = explode(':', $header, 2);

        return [
            'username' => $header[0],
            'password' => isset($header[1]) ? $header[1] : null,
        ];
    }
}