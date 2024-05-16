<?php

namespace Dangje\WebFramework\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    private string $protocolVersion = '1.0';
    private array $headers;
    private StreamInterface $body;
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;

    public function __construct(
        string $requestTarget,
        string $method,
        UriInterface $uri,
        ?array $headers = [],
        ?StreamInterface $body = null,
    ) {
        $this->headers = $headers;
        $this->body = $body;
        $this->requestTarget = $requestTarget;
        $this->method = $method;
        $this->uri = $uri;
    }


    #[\Override]  public function getProtocolVersion(): string
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

    #[\Override] public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    #[\Override] public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        unset($new->requestTarget = $requestTarget);
        return $new;
    }

    #[\Override] public function getMethod(): string
    {
        return $this->method;
    }

    #[\Override] public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        unset($new->method = $method);
        return $new;
    }

    #[\Override] public function getUri(): UriInterface
    {
        return $this->uri;
    }

    #[\Override] public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        unset($new->uri = $uri);
        return $new;
    }
}