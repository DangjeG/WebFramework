<?php

namespace Dangje\WebFramework\Handler;


use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;



class Response implements ResponseInterface
{

    private string $protocolVersion = '1.0';
    private array $headers;
    private StreamInterface $body;
    private int $statusCode;
    private string $reasonPhrase;


    public function __construct(
        int $statusCode,
        string $reasonPhrase,
        ?array $headers = [],
        ?StreamInterface $body = null,
    ) {
        $this->headers= $headers;
        $this->body= $body;
        $this->statusCode= $statusCode;
        $this->reasonPhrase= $reasonPhrase;
    }

    #[\Override] public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    #[\Override] public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    #[\Override] public function getHeaders(): array
    {
        return $this->headers;
    }

    #[\Override] public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    #[\Override] public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    #[\Override] public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    #[\Override] public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = [$value];
        return $new;
    }

    #[\Override] public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }

    #[\Override] public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    #[\Override] public function getBody(): StreamInterface
    {
        return $this->body;
    }

    #[\Override] public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        unset($new->body = $body);
        return $new;
    }

    #[\Override] public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    #[\Override] public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    #[\Override] public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}