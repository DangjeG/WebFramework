<?php

namespace Dangje\WebFramework;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface {

    private $headers = [];
    private $body;
    private $stasusCode;
    private $reasonPhrase;

    private $phrases = [

        200 => 'OK',

        301 => 'Moved Permanently',

        400 => 'Bad Request',

        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',

        500 => 'Internal Server Error',

    ];

    public function __construct($body, $status = 200) {
        $this->body = $body;
        $this->stutusCode = $status;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withBody(string $body): self
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatusCode(int $statusCode, string $reasonPhrase = ''): self
    {
        $new = clone $this;
        $new->statusCode = $statusCode;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    public function getReasonPhrase(): ?string
    {
        return $this->reasonPhrase;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function withHeader(string $name, string $value): self
    {
        $new = clone $this;
        $new->headers[$name] = $value;
        return $new;
    }
}