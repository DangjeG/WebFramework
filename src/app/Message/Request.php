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
        $this->body = is_null($body) ? new Stream() : $body;
        $this->requestTarget = $requestTarget;
        $this->method = $method;
        $this->uri = $uri;
    }



    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }


    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }


    public function getHeaders(): array
    {
        return $this->headers;
    }


    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }


    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }


    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }


    public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = [$value];
        return $new;
    }


    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name][] = $value;
        return $new;
    }


    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }


    public function getBody(): StreamInterface
    {
        return $this->body;
    }


    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }


    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }


    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }


    public function getMethod(): string
    {
        return $this->method;
    }


    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }


    public function getUri(): UriInterface
    {
        return $this->uri;
    }


    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}